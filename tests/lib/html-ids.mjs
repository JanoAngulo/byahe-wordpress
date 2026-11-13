/**
 * Every value of a real `id` attribute in an HTML string. The attribute name
 * must stand alone, so `data-id` or `grid` never count.
 */
export function htmlIds(html) {
  return new Set([...html.matchAll(/[\s"']id\s*=\s*(["'])(.*?)\1/g)].map((m) => m[2]));
}
