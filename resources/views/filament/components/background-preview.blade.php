<div x-data="backgroundPreview()" x-init="init()" class="space-y-2">
    <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
        <span>Taustan esikatselu</span>
        <span x-text="modeLabel" class="font-medium"></span>
    </div>
    <div id="background-style-preview" class="rounded-lg border border-gray-300 dark:border-gray-600 h-32 w-full overflow-hidden relative">
        <div id="background-style-preview-inner" class="absolute inset-0 flex items-center justify-center text-sm font-medium text-gray-700 dark:text-gray-200 select-none">
            <span x-text="previewText"></span>
        </div>
    </div>
</div>

<script>
function backgroundPreview(){
    return {
        mode: 'solid',
        type: 'linear',
        direction: 'to right',
        solid: '#f3f4f6',
        start: '#3b82f6',
        end: '#8b5cf6',
        modeLabel: '',
        previewText: 'Taustan esikatselu',
        init(){
            this.pull();
            this.render();
            // Päivitykset Livewire-viesteistä
            document.addEventListener('livewire:updated', () => {
                this.pull();
                this.render();
            });
        },
        pull(){
            if(!this.$wire) return;
            // Turvallisesti yritetään hakea
            try { this.mode = this.$wire.get('data.background_mode') ?? this.mode; } catch(e){}
            try { this.type = this.$wire.get('data.gradient_type') ?? this.type; } catch(e){}
            try { this.direction = this.$wire.get('data.gradient_direction') ?? this.direction; } catch(e){}
            try { this.solid = this.$wire.get('data.background_color') ?? this.solid; } catch(e){}
            try { this.start = this.$wire.get('data.gradient_start_color') ?? this.start; } catch(e){}
            try { this.end = this.$wire.get('data.gradient_end_color') ?? this.end; } catch(e){}
        },
        buildCss(){
            if(this.mode === 'gradient') {
                if(this.type === 'radial') {
                    return `radial-gradient(circle at center, ${this.start}, ${this.end})`;
                }
                return `linear-gradient(${this.direction}, ${this.start}, ${this.end})`;
            }
            return this.solid;
        },
        render(){
            const css = this.buildCss();
            const el = document.getElementById('background-style-preview-inner');
            if(el){
                el.style.background = css;
                // Kontrasti tekstiä varten
                el.style.color = this.isLightBackground() ? '#374151' : '#ffffff';
            }
            this.modeLabel = this.mode === 'gradient' ? (this.type === 'radial' ? 'Radiaalinen gradient' : 'Lineaarinen gradient') : 'Yksivärinen';
            this.previewText = this.mode === 'gradient' ? 'Gradient-esikatselu' : 'Yksivärinen tausta';
            // Päivitä koko sivun body (yksi style-tag)
            const styleTagId = 'app-background-style';
            const existing = document.getElementById(styleTagId);
            const bodyCss = `body{background:${css} !important;} .fi-body{background:${css} !important;}`;
            if(existing){
                existing.innerHTML = bodyCss;
            } else {
                const tag = document.createElement('style');
                tag.id = styleTagId;
                tag.innerHTML = bodyCss;
                document.head.appendChild(tag);
            }
        },
        isLightBackground(){
            // Arvioi ensimmäisestä väristä (solid tai gradientin start)
            const sample = this.mode === 'gradient' ? this.start : this.solid;
            const m = sample.replace('#','');
            if(m.length===3){
                const r = parseInt(m[0]+m[0],16), g=parseInt(m[1]+m[1],16), b=parseInt(m[2]+m[2],16);
                return (0.299*r + 0.587*g + 0.114*b) > 180;
            }
            if(m.length!==6) return true;
            const r = parseInt(m.substring(0,2),16), g=parseInt(m.substring(2,4),16), b=parseInt(m.substring(4,6),16);
            return (0.299*r + 0.587*g + 0.114*b) > 180;
        }
    };
}
</script>
