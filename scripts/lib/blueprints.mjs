/**
 * Builds the local and public Playground blueprints from one step list
 * (blueprints/steps.json).
 *
 * A step with `"only": "local"` goes into the local blueprint only. The local
 * blueprint runs against mounted theme and plugin folders, so it activates
 * them; the public one installs them from the release zips instead, with
 * activation, and logs the visitor in.
 */

const SCHEMA = 'https://playground.wordpress.net/blueprint-schema.json';
// Playground's CORS proxy. It also routes GitHub's redirect to the signed asset URL through itself.
const CORS_PROXY = 'https://wordpress-playground-cors-proxy.net/?';
const ACTIVATE_STEPS = new Set(['activateTheme', 'activatePlugin']);

const base = () => ({
  $schema: SCHEMA,
  landingPage: '/',
  preferredVersions: { php: '8.2', wp: 'latest' },
});

const strip = ({ only, ...step }) => step;

/** Proxied URL of a release asset; GitHub release downloads send no CORS headers. */
export function releaseUrl(repo, tag, file) {
  return `${CORS_PROXY}https://github.com/${repo}/releases/download/${tag}/${file}`;
}

/**
 * @param {object[]} steps Shared blueprint steps.
 * @param {{ repo: string, tag: string }} release GitHub repo (owner/name) and release tag.
 * @returns {{ local: object, public: object }}
 */
export function buildBlueprints(steps, { repo, tag }) {
  const local = { ...base(), steps: steps.map(strip) };

  const install = [
    {
      step: 'installTheme',
      themeData: { resource: 'url', url: releaseUrl(repo, tag, 'byahe-theme.zip') },
      options: { activate: true },
    },
    {
      step: 'installPlugin',
      pluginData: { resource: 'url', url: releaseUrl(repo, tag, 'byahe-core.zip') },
      options: { activate: true },
    },
  ];
  const shared = steps.filter((s) => s.only !== 'local' && !ACTIVATE_STEPS.has(s.step)).map(strip);
  const pub = { ...base(), login: true, steps: [...install, ...shared] };

  return { local, public: pub };
}
