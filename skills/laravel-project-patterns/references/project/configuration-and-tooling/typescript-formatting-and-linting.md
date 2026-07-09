# TypeScript, Formatting, and Linting

## When To Use

Read this focused reference when the task involves typescript, formatting, and linting.

## Pattern

### TypeScript, Formatting, and Linting

Use strict TypeScript and path aliases without emitting build artifacts from
the type checker.

```json
{
  "compilerOptions": {
    "allowArbitraryExtensions": true,
    "allowImportingTsExtensions": false,
    "allowJs": false,
    "erasableSyntaxOnly": true,
    "forceConsistentCasingInFileNames": true,
    "lib": ["ES2023", "DOM"],
    "jsx": "react-jsx",
    "module": "esnext",
    "moduleDetection": "force",
    "moduleResolution": "bundler",
    "noEmit": true,
    "noFallthroughCasesInSwitch": true,
    "noUncheckedIndexedAccess": true,
    "noUncheckedSideEffectImports": true,
    "noUnusedLocals": true,
    "noUnusedParameters": true,
    "paths": {
      "#/*": ["./resources/js/*"]
    },
    "skipLibCheck": true,
    "strict": true,
    "strictNullChecks": true,
    "target": "es2023",
    "tsBuildInfoFile": "./node_modules/.tmp/tsconfig.app.tsbuildinfo",
    "types": ["vite/client"],
    "useDefineForClassFields": true,
    "verbatimModuleSyntax": true
  },
  "exclude": ["resources/js/actions/ExamplePackage"],
  "include": ["resources/js"]
}
```

The root TypeScript config uses project references for application and tooling
inputs. Keep the Vite config in its own Node-targeted project.

```json
{
  "files": [],
  "references": [
    {
      "path": "./tsconfig.app.json"
    },
    {
      "path": "./tsconfig.node.json"
    }
  ]
}
```

The Node-targeted project repeats the bundler and strictness contract instead
of inheriting browser globals:

```json
{
  "compilerOptions": {
    "allowImportingTsExtensions": false,
    "erasableSyntaxOnly": true,
    "lib": ["ES2023"],
    "module": "esnext",
    "moduleDetection": "force",
    "moduleResolution": "bundler",
    "noEmit": true,
    "noFallthroughCasesInSwitch": true,
    "noUncheckedSideEffectImports": true,
    "noUnusedLocals": true,
    "noUnusedParameters": true,
    "skipLibCheck": true,
    "strict": true,
    "target": "es2023",
    "tsBuildInfoFile": "./node_modules/.tmp/tsconfig.node.tsbuildinfo",
    "types": ["node"],
    "verbatimModuleSyntax": true
  },
  "include": ["vite.config.ts"]
}
```

Formatter configuration owns import groups, package-script ordering, and
Tailwind class sorting:

```json
{
  "bracketSpacing": false,
  "semi": false,
  "singleQuote": true,
  "sortImports": {
    "groups": [
      ["builtin"],
      ["external"],
      ["internal"],
      ["parent", "sibling", "index"],
      ["unknown"]
    ],
    "internalPattern": ["#/"]
  },
  "sortPackageJson": {
    "sortScripts": true
  },
  "sortTailwindcss": {
    "functions": ["cn"],
    "stylesheet": "./resources/js/index.css"
  }
}
```

The linter enables type-aware checks, errors on stale disable directives, and
keeps the project-specific cycle rule explicit:

```json
{
  "options": {
    "reportUnusedDisableDirectives": "error",
    "typeAware": true,
    "typeCheck": true
  },
  "plugins": ["eslint", "import", "jsx-a11y", "oxc", "promise", "react", "typescript", "unicorn"],
  "rules": {
    "import/no-cycle": "error"
  }
}
```

Keep generated, runtime, and tool-cache paths in the formatter's
`ignorePatterns`; do not copy project-specific ignored package namespaces into
another project without checking its generated paths.

## Related References

- [`../configuration-and-tooling.md`](../configuration-and-tooling.md)
