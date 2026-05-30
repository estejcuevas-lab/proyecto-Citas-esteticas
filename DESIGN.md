---
name: citas-app
description: Plataforma warm-minimal de reserva de citas estéticas con marca por negocio
colors:
  primary: "#994b35"
  primary-deep: "#6a2d1e"
  primary-soft: "#f1e3d7"
  app-bg: "#f6f1eb"
  app-surface: "#fffbf7"
  app-surface-strong: "#fffaf5"
  app-line: "#e1d5c8"
  app-text: "#2f241c"
  app-muted: "#746252"
  app-success-bg: "#e3f3e7"
  app-success-text: "#1f5e37"
  app-error-bg: "#fae3e3"
  app-error-text: "#8c2430"
  landing-bg: "#141210"
  landing-cream: "#f5ebd7"
  landing-gold: "#d4af37"
typography:
  display:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontWeight: 600
    lineHeight: 1.1
    letterSpacing: "-0.02em"
  body:
    fontFamily: "Inter, ui-sans-serif, system-ui, sans-serif"
    fontWeight: 400
    lineHeight: 1.5
  eyebrow:
    fontFamily: "Inter, ui-sans-serif, system-ui, sans-serif"
    fontSize: "0.75rem"
    fontWeight: 700
    letterSpacing: "0.08em"
    textTransform: uppercase
rounded:
  sm: "0.5rem"
  md: "1rem"
  lg: "1.5rem"
  xl: "1.75rem"
  pill: "9999px"
spacing:
  page: "1.25rem"
  card: "1.25rem"
  panel: "1.5rem"
  section: "2rem"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "#fffaf5"
    rounded: "{rounded.md}"
    padding: "12px 20px"
  button-primary-hover:
    backgroundColor: "{colors.primary-deep}"
    textColor: "#fffaf5"
  button-secondary:
    backgroundColor: "{colors.primary-soft}"
    textColor: "{colors.app-text}"
    rounded: "{rounded.md}"
    padding: "12px 20px"
  surface-panel:
    backgroundColor: "{colors.app-surface}"
    textColor: "{colors.app-text}"
    rounded: "{rounded.xl}"
  form-input:
    backgroundColor: "#fffdf9"
    textColor: "{colors.app-text}"
    rounded: "{rounded.md}"
    padding: "12px 16px"
---

## Overview

Sistema visual **warm minimal**: fondos crema con gradiente suave, acento terracotta por defecto, tipografía display serif para titulares y sans para UI. Stack: **Laravel 11 + Blade**, **Tailwind CSS v4** (`resources/css/app.css`), **Alpine.js**, **Vite**, iconos **Heroicons**.

Dos capas de color:

1. **Plataforma** — tokens en `:root` (`--app-*`, `--primary-*` por defecto).
2. **Negocio** — `--primary-color` (y derivados) inyectados vía `@section('theme_style')` en `body` solo en páginas con contexto de negocio.

Catálogo público usa tarjetas marketplace (`.business-market-card`); perfiles de negocio pueden usar secciones `.landing-*` (hero oscuro opcional). Paneles autenticados usan `.page-shell`, `.surface-panel`, `.surface-card`.

## Colors

| Rol | Token | Uso |
|-----|--------|-----|
| Primario | `--primary-color` | CTAs, acentos, iconos en contexto de marca |
| Primario profundo | `--primary-color-deep` | Gradientes de botón, títulos de acento |
| Primario suave | `--primary-soft` | Pills, eyebrows, fondos de chip |
| Fondo app | `--app-bg` | Body gradient base |
| Superficie | `--app-surface` / `--app-surface-strong` | Paneles y cards |
| Borde | `--app-line` | Bordes de card y inputs |
| Texto | `--app-text` | Cuerpo y títulos en UI clara |
| Muted | `--app-muted` | Secundario; mantener ≥4.5:1 sobre fondos claros |
| Éxito / error | `--app-success-*`, `--app-error-*` | Flash, pills de estado |
| Landing oscuro | `--landing-bg`, `--landing-cream`, `--landing-gold` | Hero público de negocio |

Gradiente de botón primario: `linear-gradient(135deg, var(--primary-color), var(--primary-color-deep))`.

Body: `radial-gradient` sutil + `linear-gradient` crema (`app.css` `@layer base`).

## Typography

- **Display**: Cormorant Garamond — `.font-display`, `.page-title`, `.section-title`.
- **UI / body**: Inter — `body`, labels, botones, formularios.
- **Eyebrow**: `.eyebrow` — uppercase, tracking amplio, fondo `primary-soft`.
- Escala: títulos de página `text-4xl`–`5xl`; sección `text-2xl`–`3xl`; cuerpo `text-sm` base con `leading-relaxed` en `.text-muted`.

Cargar fuentes vía Google Fonts en `layouts/app.blade.php` (ya configurado).

## Elevation

Sombras suaves, no material heavy:

- `--app-shadow`: `0 24px 64px rgb(89 61 43 / 0.12)` en `.surface-panel`.
- Cards: borde `1px solid var(--app-line)` + fondo sólido antes que sombra fuerte.
- Marketplace cards: borde `#e4d8cb`, `rounded-3xl`, imagen de portada full-bleed arriba.
- Landing oscuro: overlays en gradiente sobre imagen, sin sombra exagerada en contenido.

## Components

| Componente | Clases / patrón |
|------------|------------------|
| Layout | `layouts.app`, `page-shell`, `page-width` |
| Panel | `.surface-panel`, `.surface-card`, legacy `.surface`, `.card` |
| CTA primario | `.brand-button`, `.btn-primary`, `.btn.btn-primary` |
| CTA secundario | `.btn-secondary`, `.button.secondary` |
| Formulario | `.form-input`, `label` + `input/select/textarea` en `@layer components` |
| Catálogo | `.catalog-grid`, `.business-market-card`, `.market-filter-chip` |
| Marca negocio | `brand-color-picker` partial, `@section('theme_style')` |
| Servicios | `.service-image-grid`, `.service-image-card` |
| Reseñas | `.review-carousel`, lightbox Alpine en `public-show` |
| Flash | `.flash-success`, `.flash-error` + partials con Alpine `x-show` |
| Vacío | `.empty-state` |
| Pills estado | `.pill`, variantes `.pending`, `.confirmed`, etc. |

Iconos: `<x-heroicon-o-*>` (Blade UI Kit). Evitar emojis como iconografía principal.

Interacción: Alpine para nav móvil, filtros de categoría, lightbox, chips activos (`is-active`).

## Do's and Don'ts

**Do**

- Reutilizar tokens y clases de `resources/css/app.css`.
- Aplicar `@section('theme_style')` con `--primary-color` del negocio en perfil público y flujo de reserva preseleccionado.
- Mantener jerarquía: un `.page-title`, CTAs en `.actions`, copy secundario en `.text-muted`.
- Usar `rounded-2xl` / `rounded-3xl` y espaciado generoso (`gap-4`, `p-5`–`p-6`).
- Diseñar tarjetas de catálogo con imagen, rating, servicios destacados y enlace claro al perfil.

**Don't**

- Hardcodear hex de marca en vistas globales (dashboard admin, login) salvo tokens por defecto en `:root`.
- Añadir bloques `<style>` por vista si Tailwind o `app.css` bastan.
- Usar gris pálido (#999) para párrafos largos sobre crema (fallo de contraste típico AI).
- Mezclar estética landing oscura en formularios de panel sin motivo.
- Crear nuevos sistemas de botón; extender `.btn` / `.brand-button`.
