// frontend/eslint.config.js  (ESLint 9 flat config)
import js from '@eslint/js';
import globals from 'globals';
import jsxA11y from 'eslint-plugin-jsx-a11y';
import reactHooks from 'eslint-plugin-react-hooks';
import reactRefresh from 'eslint-plugin-react-refresh';

export default [
  { ignores: ['dist', 'node_modules', 'qa', 'backups', 'test-results', 'playwright-report', 'media-src', 'media-archive', 'public'] },
  js.configs.recommended,
  jsxA11y.flatConfigs.recommended,
  reactHooks.configs.flat.recommended,
  reactRefresh.configs.vite,
  {
    files: ['**/*.{js,jsx}'],
    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',
      globals: globals.browser,
      parserOptions: { ecmaFeatures: { jsx: true } },
    },
    rules: {
      // Components used only as <Foo /> are not seen as "used" without eslint-plugin-react.
      'no-unused-vars': ['error', { varsIgnorePattern: '^[A-Z_]', argsIgnorePattern: '^[A-Z_]' }],
      // Existing data-fetching effects reset their state first. Kept as written (see REDESIGN-REPORT.md).
      'react-hooks/set-state-in-effect': 'warn',
    },
  },
  {
    files: ['scripts/**', 'e2e/**', '*.config.js'],
    languageOptions: { globals: globals.node },
  },
];
