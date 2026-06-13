<script setup>
import { Head } from '@inertiajs/vue3'
import { onMounted } from 'vue'

const atlasScripts = [
    { src: '/atlas/js/assets.js' },
    { src: '/atlas/js/sfx.js' },
    { src: '/atlas/js/plugins.js' },
    { src: '/atlas/js/data.js' },
    { src: '/atlas/js/editor.js', module: true },
]

function loadStylesheet(href) {
    if (document.querySelector(`link[href="${href}"]`)) return

    const link = document.createElement('link')
    link.rel = 'stylesheet'
    link.href = href
    document.head.appendChild(link)
}

function loadScript(entry) {
    return new Promise((resolve, reject) => {
        const src = typeof entry === 'string' ? entry : entry.src
        const existing = document.querySelector(`script[src="${src}"]`)
        if (existing) {
            resolve()
            return
        }

        const script = document.createElement('script')
        script.src = src
        if (entry && entry.module) script.type = 'module'
        script.onload = resolve
        script.onerror = reject
        document.body.appendChild(script)
    })
}

onMounted(async () => {
    window.RPGATLAS_BASE = '/atlas/'
    loadStylesheet('/atlas/css/editor.css')

    for (const src of atlasScripts) {
        await loadScript(src)
        if (typeof src === 'object' && src.src === '/atlas/js/data.js') {
            window.RPGAtlasDeps = {
                Assets: window.Assets,
                AtlasBuiltins: window.AtlasBuiltins,
                DataDefaults: window.DataDefaults,
                GLRender: window.GLRender,
                Music: window.Music,
                RA: window.RA,
                Sfx: window.Sfx,
            }
        }
    }
})
</script>

<template>
    <Head title="RPGAtlas - Editor">
        <base href="/atlas/">
    </Head>

    <header id="menubar">
        <span id="logo"><svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><defs><radialGradient id="ra-sea" cx="50%" cy="40%" r="65%"><stop offset="0%" stop-color="#2e5887"/><stop offset="100%" stop-color="#142339"/></radialGradient><linearGradient id="ra-gold" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#ffe2a0"/><stop offset="100%" stop-color="#d9a13f"/></linearGradient></defs><circle cx="32" cy="32" r="28" fill="url(#ra-sea)" stroke="url(#ra-gold)" stroke-width="3"/><g fill="none" stroke="#8fb4e0" stroke-width="1" opacity="0.45"><ellipse cx="32" cy="32" rx="11" ry="26"/><ellipse cx="32" cy="32" rx="21" ry="26"/><path d="M6.5 32h51M10 20h44M10 44h44"/></g><g fill="#7fb069" opacity="0.85"><rect x="17" y="21" width="5" height="5"/><rect x="22" y="18" width="5" height="5"/><rect x="22" y="23" width="4" height="4"/><rect x="39" y="37" width="5" height="5"/><rect x="42" y="33" width="4" height="4"/></g><polygon points="38,32 44,44 32,38 20,44 26,32 20,20 32,26 44,20" fill="#e8d9b0" stroke="#142339" stroke-width="0.8" stroke-linejoin="round"/><path d="M32 5 L36.4 27.6 L32 32 Z M59 32 L36.4 36.4 L32 32 Z M32 59 L27.6 36.4 L32 32 Z M5 32 L27.6 27.6 L32 32 Z" fill="url(#ra-gold)" stroke="#142339" stroke-width="0.8" stroke-linejoin="round"/><path d="M32 5 L27.6 27.6 L32 32 Z M59 32 L36.4 27.6 L32 32 Z M32 59 L36.4 36.4 L32 32 Z M5 32 L27.6 36.4 L32 32 Z" fill="#a8762a" stroke="#142339" stroke-width="0.8" stroke-linejoin="round"/><circle cx="32" cy="32" r="3.6" fill="url(#ra-gold)" stroke="#142339" stroke-width="1"/></svg>RPG<span class="thin">Atlas</span></span>
        <nav id="menus"></nav>
    </header>

    <div id="toolbar"></div>

    <div id="main">
        <aside id="sidebar">
            <section>
                <div class="side-head">Maps
                    <span class="side-tools">
                        <button id="map-add" class="mini" title="New map">+</button>
                        <button id="map-del" class="mini" title="Delete map">-</button>
                        <button id="map-gen" class="mini" title="Generate random map">Random</button>
                    </span>
                </div>
                <ul id="maplist"></ul>
            </section>

            <section id="palette-section">
                <div class="side-head">Tiles <span class="dim">(right-click map = pick)</span></div>
                <div id="palettewrap"><canvas id="palette"></canvas></div>
            </section>
        </aside>

        <div id="mapscroll">
            <canvas id="mapcanvas"></canvas>
        </div>
    </div>

    <footer id="status">
        <span id="status-text">Ready</span>
        <span class="spacer"></span>
        <span id="zoom-ind" title="Zoom">75%</span>
        <span id="save-ind">saved</span>
    </footer>
    <input id="import-file" type="file" accept=".json,application/json" hidden>
    <div id="modal-root"></div>
</template>
