<script setup>
import { Head } from '@inertiajs/vue3'
import { onMounted } from 'vue'

const atlasScripts = [
    '/atlas/js/assets.js',
    '/atlas/js/sfx.js',
    '/atlas/js/plugins.js',
    '/atlas/js/data.js',
    '/atlas/js/engine.js',
]

function loadStylesheet(href) {
    if (document.querySelector(`link[href="${href}"]`)) return

    const link = document.createElement('link')
    link.rel = 'stylesheet'
    link.href = href
    document.head.appendChild(link)
}

function loadScript(src) {
    return new Promise((resolve, reject) => {
        const existing = document.querySelector(`script[src="${src}"]`)
        if (existing) {
            resolve()
            return
        }

        const script = document.createElement('script')
        script.src = src
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
    }
})
</script>

<template>
    <Head title="RPGAtlas Player" />

    <div id="stage">
        <canvas id="gamecanvas"></canvas>
    </div>
</template>
