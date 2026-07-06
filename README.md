# tiritaito-docs | Índice Maestro

**Versión**: propuesta de arquitectura documental
**Última actualización**: 2026-07-06
**Dueño**: tu (coordinación documentación)

---

## 📋 Propósito

Este repositorio centraliza la documentación de tiritaito bajo una arquitectura clara:
- **1 dueño de contenido por documento**
- **1 audiencia específica por documento**
- **0 solapamientos** (cada documento tiene función única)
- **Git gestiona versiones** (sin sufijos de versión en nombres)

---

## 📚 Estructura de Documentos

### **00-nucleo-tecnico/** | Documentación técnica permanente

| Documento | Audiencia | Dueño | Propósito | Frecuencia |
|-----------|-----------|-------|----------|------------|
| **00_CORE.md** | Hno A | Técnico permanente | Especificación central del núcleo técnico | A demanda |
| **01_CREATORS_APP.md** | Hno A | Técnico permanente | Documentación de la app Creators | A demanda |
| **02_REF_PODCAST.md** | Hno A | Técnico permanente | Referencia de podcast | A demanda |
| **04_ENTORNO_LOCAL.md** | Hno A + Frente 1 | Técnico permanente | Setup local (contiene datos sensibles) | A demanda |

### **01-producto/** | Visión y alcance del producto

| Documento | Audiencia | Dueño | Propósito | Frecuencia |
|-----------|-----------|-------|----------|------------|
| **ALCANCE_WEB_NUEVA.md** | Hna C | Frente 2 | Alcance de la nueva web (v1.1 actual, sin sufijo) | Por iteración |

### **02-metodologia/** | Procesos y flujos de construcción

| Documento | Audiencia | Dueño | Propósito | Frecuencia |
|-----------|-----------|-------|----------|------------|
| **METODOLOGIA_CONSTRUCCION.md** | Hno A + Hna C | Frentes 2 y 3 | Metodología de construcción (fusiona v2 + diagnóstico v1 como anexo histórico) | Por iteración |
| **MIGRACION_CONTENIDO.md** | Hna C + Hno A + tú | Frente 2 directamente | Flujo: qué se audita, qué se migra, cómo se construye | Por iteración |

### **03-guias-practicas/** | Guías paso a paso para ejecución

| Documento | Audiencia | Dueño | Propósito | Frecuencia |
|-----------|-----------|-------|----------|------------|
| **GUIA_AVADA_LOCAL.md** | Hno A | Frentes 1 y 3 directamente | Guía paso a paso local + roadmap Fase 0-1 | Por iteración |

### **04-vision-y-equipo/** | Estrategia, arquitectura y coordinación

| Documento | Audiencia | Dueño | Propósito | Frecuencia |
|-----------|-----------|-------|----------|------------|
| **ARQUITECTURA_Y_ROADMAP.md** | tú (visión de conjunto) | Transversal | FODA, fases, visión general | Trimestral |
| **ORGANIZACION_EQUIPO_Y_HERRAMIENTAS.md** | tú | Coordinación | Mapa final de proyectos, herramientas, límites y configuración | A demanda |

### **historico/** | Archivo y documentos obsoletos

| Documento | Audiencia | Dueño | Propósito | Frecuencia |
|-----------|-----------|-------|----------|------------|
| **INFORME_ESTRATEGICO_2026_1.md** | Referencia | Archivo | Informe original 2026 | No se actualiza |
| **INVESTIGACION_HERRAMIENTAS_2026.md** | Referencia | Archivo | Investigación original | No se actualiza |

---

## 🔄 Relaciones Entre Documentos (Sin Solapamiento)

```
00_CORE ──┐
          ├→ METODOLOGIA_CONSTRUCCION ──→ MIGRACION_CONTENIDO ──→ Frente 2
01_CREATORS ┤
02_REF_PODCAST ┤
04_ENTORNO ──┘
             └→ GUIA_AVADA_LOCAL ──→ Frentes 1 y 3

ALCANCE_WEB ──┐
              ├→ METODOLOGIA_CONSTRUCCION (audiencia complementaria)
              └→ MIGRACION_CONTENIDO (audiencia complementaria)

ARQUITECTURA_Y_ROADMAP ←── (visión general, sin detalles técnicos)
ORGANIZACION_EQUIPO_Y_HERRAMIENTAS ←── (coordinación)
```

---

## ✏️ Cómo Usar Este Repositorio

1. **Encuentra tu documento**: usa la tabla arriba según tu rol y objetivo
2. **Consulta el dueño**: antes de cambios grandes, verifica quién es responsable
3. **Respeta audiencias**: cada documento sirve a alguien específico
4. **Usa Git para versiones**: git log te muestra el historial (no sufijos v1, v2)
5. **Consulta historico/** si necesitas contexto original
