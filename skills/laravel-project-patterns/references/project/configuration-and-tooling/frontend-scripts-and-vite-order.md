# Frontend Scripts and Vite Order

## When To Use

Read this focused reference when the task involves frontend scripts and vite order.

## Pattern

### Frontend Scripts and Vite Order

The frontend script graph mirrors Composer:

```json
{
  "scripts": {
    "build": "tsgo -b && vite build",
    "dev": "vite",
    "fix": "oxlint --fix --fix-dangerously --fix-suggestions --tsconfig tsconfig.json",
    "fmt": "oxfmt",
    "fmt:check": "oxfmt --check",
    "lint": "oxlint --tsconfig tsconfig.json",
    "prebuild": "composer wayfinder && composer typescript",
    "preview": "vite preview"
  },
  "devEngines": {
    "packageManager": {
      "name": "nub",
      "version": "^0.6.0",
      "onFail": "warn"
    }
  },
  "allowBuilds": {
    "@sentry/cli": true,
    "core-js": true,
    "lefthook": true
  }
}
```

Keep generated modules out of authored-source examples. Document their
generation and typed imports, but do not copy generated action or enum output
into the skill.

```ts
import inertia from '@inertiajs/vite'
import {wayfinder} from '@laravel/vite-plugin-wayfinder'
import optimizeLocales from '@react-aria/optimize-locales-plugin'
import babel from '@rolldown/plugin-babel'
import {sentryVitePlugin} from '@sentry/vite-plugin'
import tailwindcss from '@tailwindcss/vite'
import react, {reactCompilerPreset} from '@vitejs/plugin-react'
import laravel from 'laravel-vite-plugin'
import {google} from 'laravel-vite-plugin/fonts'
import {defineConfig, loadEnv} from 'vite'
import manifestSRI from 'vite-plugin-manifest-sri'
import {run} from 'vite-plugin-run'
import tsconfigPaths from 'vite-tsconfig-paths'

export default defineConfig(({mode}) => {
  const env = loadEnv(mode, process.cwd(), '')

  return {
    build: {
      cssMinify: 'lightningcss',
      sourcemap: 'hidden',
    },
    plugins: [
      inertia(),
      laravel({
        fonts: [
          google('Inter', {
            alias: 'sans',
            display: 'swap',
            fallbacks: ['system-ui', 'sans-serif'],
            preload: [{weight: 400}, {weight: 600}],
            styles: ['normal'],
            subsets: ['latin'],
            weights: [100, 200, 300, 400, 500, 600, 700, 800, 900],
          }),
        ],
        input: ['resources/js/app.tsx'],
        refresh: true,
      }),
      manifestSRI(),
      {
        ...optimizeLocales.vite({
          locales: ['en-US', 'es-ES'],
        }),
        enforce: 'pre',
      },
      react(),
      babel({presets: [reactCompilerPreset()]}),
      run({
        silent: false,
        input: {
          name: 'typescript transform',
          run: ['composer', 'typescript'],
          pattern: 'app/Enums/**/*.php',
          build: false,
        },
      }),
      tailwindcss(),
      tsconfigPaths(),
      wayfinder({routes: false}),
      sentryVitePlugin({
        authToken: env.SENTRY_AUTH_TOKEN,
        org: 'example-org',
        project: 'example-app',
        release: {
          name: env.APP_RELEASE,
        },
      }),
    ],
  }
})
```

## Related References

- [`../configuration-and-tooling.md`](../configuration-and-tooling.md)
