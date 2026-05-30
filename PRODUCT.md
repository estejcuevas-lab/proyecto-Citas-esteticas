# Product

## Register

product

## Users

**Clientes** buscan salones y centros de belleza, comparan servicios con fotos y reseñas, y reservan citas. Entran desde el catálogo público o el perfil de un negocio; su contexto es exploración y decisión rápida, no administración.

**Dueños de negocio** gestionan marca (color primario), servicios con galería, horarios, citas y pagos. Necesitan un panel claro y operativo sin ruido visual.

**Administradores** supervisan la plataforma multinegocio. Priorizan eficiencia y legibilidad sobre marketing.

## Product Purpose

citas-app es una plataforma de reserva de citas para estética y belleza (uñas, spa, barbería, etc.). Conecta negocios activos con clientes: descubrimiento tipo marketplace, perfil público por negocio, reserva con horarios disponibles y reseñas verificadas tras cita completada.

Éxito = el cliente encuentra un negocio confiable, reserva sin fricción, y el dueño mantiene su marca visible en su landing sin contaminar el resto de la app.

## Brand Personality

Cálido, refinado y confiable. Sensación de boutique digital (spa / salón premium), no de plantilla genérica. Tres palabras: **fluido**, **minimalista**, **acogedor**.

Tono de copy en español (México/LATAM neutro): directo, cercano, sin jerga técnica en flujos de cliente.

Referencias implícitas en el producto: Fresha/Booksy para descubrimiento y tarjetas de negocio; Apple/SaaS moderno para espaciado y jerarquía; detalle editorial en titulares (Cormorant) con UI funcional en Inter.

## Anti-references

- Plantillas “AI slop”: gradientes morados, Inter + purple, cards idénticas sin jerarquía.
- Dashboards densos tipo ERP con tablas grises sin aire.
- Bootstrap/Material por defecto sin personalización.
- Mezclar el color de marca de un negocio en pantallas globales (admin, catálogo general, login).
- Landings oscuras de lujo en flujos operativos (formularios de cita, panel) donde el usuario espera claridad y contraste.
- Animaciones llamativas que distraen de reservar o completar un formulario.

## Design Principles

1. **Marca donde hay negocio** — El color `--primary-color` del negocio solo en contexto de ese negocio (perfil público, reserva preseleccionada, edición de su marca). El shell global mantiene identidad de plataforma (warm cream / terracotta por defecto).

2. **Confianza antes que decoración** — Fotos de servicios, reseñas con imágenes y datos claros (precio, duración, rating) reducen incertidumbre; el diseño no compite con el contenido.

3. **Una acción principal por pantalla** — Reservar, guardar servicio, publicar reseña: un CTA primario evidente; secundarios discretos.

4. **Consistencia por componentes compartidos** — Layouts Blade, clases en `app.css` (`surface-panel`, `brand-button`, `market-card`) antes de estilos ad hoc por vista.

5. **Accesible por defecto** — Contraste legible en texto de cuerpo, focos visibles en formularios, estados vacíos y errores explícitos.

## Accessibility & Inclusion

- Objetivo **WCAG 2.1 AA** en texto, controles y estados de foco.
- UI principal en **español**; mantener `lang="es"` en HTML.
- Respetar `prefers-reduced-motion` en animaciones futuras (Alpine/transiciones).
- No depender solo del color para estado (citas: pills con texto; errores con copy + estilo).
- Validar contraste cuando un negocio elige color de marca muy claro; ofrecer fallback en `brandColor()` si hace falta.
