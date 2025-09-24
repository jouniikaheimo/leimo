#!/usr/bin/env bash
set -euo pipefail

# --- Konfigipolut ---
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$SCRIPT_DIR"          # jos skripti on juuresta, muuten säädä
PROJECT_CFG="$PROJECT_DIR/.codex/config.toml"

GLOBAL_DIR="$HOME/.codex"
GLOBAL_CFG="$GLOBAL_DIR/config.toml"
BACKUP_CFG="$GLOBAL_DIR/config.toml.bak.$(date +%s)"

# --- Tarkistukset ---
if [[ ! -f "$PROJECT_CFG" ]]; then
  echo "❌ Ei löytynyt $PROJECT_CFG. Luo projektikohtainen .codex/config.toml ensin."
  exit 1
fi
mkdir -p "$GLOBAL_DIR"

# --- Palautus funktio ---
restore() {
  # Palauta backup jos sellainen on, muuten poista mahdollinen symlink/kopio
  if [[ -f "$BACKUP_CFG" ]]; then
    mv -f "$BACKUP_CFG" "$GLOBAL_CFG"
    echo "↩️  Palautettu globaali config: $GLOBAL_CFG"
  else
    # Ei varmuuskopiota -> poista projektin kopio/symlink
    if [[ -L "$GLOBAL_CFG" ]]; then rm -f "$GLOBAL_CFG"; fi
    # älä poista jos käyttäjä on luonut oman pysyvän configin ajon aikana
  fi
}
trap restore EXIT

# --- Yritä ensin env-muuttujalla (jos sovellus tukee CODEX_CONFIG) ---
export CODEX_CONFIG="$PROJECT_CFG"

# --- Fallback: tee turvallinen varmuuskopio + vaihto ---
if [[ -f "$GLOBAL_CFG" ]]; then
  cp -f "$GLOBAL_CFG" "$BACKUP_CFG"
  echo "💾 Varalla globaali config: $BACKUP_CFG"
else
  # Merkitään ettei ollut mitä palauttaa
  : > "$BACKUP_CFG"
fi

# Käytä symlinkkiä, jotta muutokset projektin configiin heijastuvat heti
ln -sfn "$PROJECT_CFG" "$GLOBAL_CFG"
echo "🔗 Käytössä projektikonfigi → $GLOBAL_CFG → $PROJECT_CFG"

# --- Käynnistä WindSurf / Codex-asiakas ---
# Valitse yksi seuraavista tavoista ympäristöstäsi riippuen:

if command -v windsurf >/dev/null 2>&1; then
  echo "🚀 Käynnistetään: windsurf"
  windsurf
elif [[ "$(uname)" == "Darwin" ]] && [[ -d "/Applications/WindSurf.app" ]]; then
  echo "🚀 Käynnistetään: WindSurf.app"
  open -a "WindSurf"
else
  # Viimeinen vaihtoehto: jos käytät Editor/CLI-binääriä nimeltä 'codex'
  if command -v codex >/dev/null 2>&1; then
    echo "🚀 Käynnistetään: codex"
    codex
  else
    echo "⚠️  En löytänyt käynnistyskomentoa (windsurf/open/codex). Käynnistä editori käsin tämän termin auki ollessa."
    read -p "Paina Enter lopettaaksesi..."
  fi
fi

# trap hoitaa palautuksen