# Guide génération d'images CYNA — Gemini Imagen

> Colle chaque prompt directement dans **Gemini** (ou **ImageFX** sur labs.google)  
> Format recommandé : **PNG 1280×720** pour les catégories, **1920×600** pour les carrousels  
> Style commun à toutes les images : voir la section "Charte visuelle" ci-dessous

---

## 🎨 Charte visuelle CYNA (à inclure dans chaque prompt)

```
Cybersecurity B2B SaaS product illustration, dark navy background (#1E1B4B),
neon cyan accent (#22d3ee), deep space atmosphere, minimal and technical,
clean flat 3D render, no text, no logos, no UI mockups, professional enterprise feel,
subtle grid lines on background, slight glow effect on main element
```

---

## 📁 Images catégories

> Chemin dans le projet : `public/images/categories/`  
> Format final attendu en base : `/images/categories/<nom>.png`  
> ⚠️ Mettre à jour le champ `image` dans EasyAdmin après upload si tu changes l'extension (.svg → .png)

---

### 1. SOC Managed
**Fichier :** `public/images/categories/soc.png`

```
Cybersecurity B2B SaaS product illustration, dark navy background (#1E1B4B),
neon cyan accent (#22d3ee), deep space atmosphere, minimal and technical,
clean flat 3D render, no text, no logos, no UI mockups, professional enterprise feel,
subtle grid lines on background, slight glow effect on main element.

Central element: a glowing shield with an eye symbol in the center, surrounded by
orbiting data streams and alert notification rings, conveying 24/7 monitoring and
threat detection. Electric blue and cyan color palette, dark background.
```

---

### 2. EDR & Postes
**Fichier :** `public/images/categories/edr.png`

```
Cybersecurity B2B SaaS product illustration, dark navy background (#1E1B4B),
neon cyan accent (#22d3ee), deep space atmosphere, minimal and technical,
clean flat 3D render, no text, no logos, no UI mockups, professional enterprise feel,
subtle grid lines on background, slight glow effect on main element.

Central element: a laptop and server connected by glowing neural network lines,
with a shield overlay blocking a red threat particle. Endpoint protection concept,
neon cyan connections, dark navy background.
```

---

### 3. XDR & Cloud
**Fichier :** `public/images/categories/xdr.png`

```
Cybersecurity B2B SaaS product illustration, dark navy background (#1E1B4B),
neon cyan accent (#22d3ee), deep space atmosphere, minimal and technical,
clean flat 3D render, no text, no logos, no UI mockups, professional enterprise feel,
subtle grid lines on background, slight glow effect on main element.

Central element: a glowing cloud shape connected to multiple nodes (AWS, Azure, GCP
style icons abstracted as hexagons), with data flow arrows and a detection radar ring.
Extended detection and response concept, neon cyan and purple tones.
```

---

### 4. Réseau Sécurisé
**Fichier :** `public/images/categories/network.png`

```
Cybersecurity B2B SaaS product illustration, dark navy background (#1E1B4B),
neon cyan accent (#22d3ee), deep space atmosphere, minimal and technical,
clean flat 3D render, no text, no logos, no UI mockups, professional enterprise feel,
subtle grid lines on background, slight glow effect on main element.

Central element: a network topology diagram with a firewall wall in the center,
glowing padlock on each connection node, secure data packets flowing through
encrypted tunnels. Zero Trust network concept, neon cyan lines on dark navy.
```

---

### 5. Formation Cyber
**Fichier :** `public/images/categories/training.png`

```
Cybersecurity B2B SaaS product illustration, dark navy background (#1E1B4B),
neon cyan accent (#22d3ee), deep space atmosphere, minimal and technical,
clean flat 3D render, no text, no logos, no UI mockups, professional enterprise feel,
subtle grid lines on background, slight glow effect on main element.

Central element: a graduation cap with a shield symbol, surrounded by floating
cybersecurity icons (phishing hook crossed out, password lock, awareness bell),
glowing in cyan. Cyber awareness training concept, clean and professional.
```

---

## 🎠 Images carrousel (hero banners)

> Chemin dans le projet : `public/images/carousel/`  
> Format : **PNG 1920×600** (format wide cinématique)  
> Mettre à jour le champ `image` dans EasyAdmin → Carousel Slides

---

### Carrousel 1 — SOC Managed
**Fichier :** `public/images/carousel/soc-hero.png`

```
Wide cinematic cybersecurity hero banner, 1920x600, dark navy background (#1E1B4B),
neon cyan (#22d3ee) glowing accents, professional B2B enterprise style, no text, no UI.

Panoramic SOC operations center concept: a large glowing shield in center-right,
surrounded by orbiting alert rings and data streams flowing left to right.
Abstract analysts silhouettes on the left blending into the dark background.
Cinematic depth of field, dramatic lighting from the shield glow.
```

---

### Carrousel 2 — EDR Nouvelle Génération
**Fichier :** `public/images/carousel/edr-hero.png`

```
Wide cinematic cybersecurity hero banner, 1920x600, dark navy background (#1E1B4B),
neon cyan (#22d3ee) glowing accents, professional B2B enterprise style, no text, no UI.

Panoramic endpoint protection concept: a row of laptop/server devices on a dark desk,
each protected by a glowing force field bubble. A red threat particle is being blocked
and disintegrated on impact. Left side dark, right side illuminated by cyan glow.
Cinematic perspective, dramatic depth.
```

---

### Carrousel 3 — XDR & Cloud
**Fichier :** `public/images/carousel/xdr-hero.png`

```
Wide cinematic cybersecurity hero banner, 1920x600, dark navy background (#1E1B4B),
neon cyan (#22d3ee) glowing accents, professional B2B enterprise style, no text, no UI.

Panoramic cloud security concept: a glowing cloud structure in center with data streams
flowing to connected cloud provider nodes (abstracted as hexagons). Radar detection
sweep overlaid across the scene. Deep space atmosphere with subtle star-like data
points. Cinematic wide angle, neon cyan and violet tones.
```

---

### Carrousel 4 — Formation Cyber
**Fichier :** `public/images/carousel/training-hero.png`

```
Wide cinematic cybersecurity hero banner, 1920x600, dark navy background (#1E1B4B),
neon cyan (#22d3ee) glowing accents, professional B2B enterprise style, no text, no UI.

Panoramic cyber training concept: abstract human figures (silhouettes) standing in
front of a large glowing shield wall, each silhouette connected to floating security
icons (lock, alert bell, phishing hook crossed out). Team empowerment feel,
warm cyan light illuminating the scene from the shield center. Cinematic wide angle.
```

---

## ✅ Checklist après génération

- [x] Renommer chaque image avec le nom de fichier indiqué ci-dessus
- [x] Déposer dans `public/images/categories/` et `public/images/carousel/`
- [ ] Dans EasyAdmin → **Catégories** : mettre à jour le champ `image` pour chaque catégorie
  - SOC Managed → `/images/categories/soc.png`
  - EDR & Postes → `/images/categories/edr.png`
  - XDR & Cloud → `/images/categories/xdr.png`
  - Reseau Securise → `/images/categories/network.png`
  - Formation Cyber → `/images/categories/training.png`
- [ ] Dans EasyAdmin → **Carousel Slides** : mettre à jour les 4 slides
- [ ] Recharger le site pour vérifier l'affichage
