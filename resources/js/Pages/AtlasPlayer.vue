<script setup>
import { Head } from '@inertiajs/vue3'
import { onMounted } from 'vue'

const atlasScripts = [
    { src: '/atlas/js/assets.js' },
    { src: '/atlas/js/sfx.js' },
    { src: '/atlas/js/plugins.js' },
    { src: '/atlas/js/data.js' },
    { src: '/atlas/js/engine.js', module: true },
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
    loadStylesheet('/atlas/css/play.css')

    for (const src of atlasScripts) {
        await loadScript(src)
        if (typeof src === 'object' && src.src === '/atlas/js/data.js') {
            window.RPGAtlasDeps = {
                Assets: window.Assets,
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
    <Head title="RPGAtlas Player">
        <base href="/atlas/">
    </Head>

    <div id="stage">
        <canvas id="gamecanvas"></canvas>
    </div>
</template>
