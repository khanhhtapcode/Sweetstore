<svg xmlns="http://www.w3.org/2000/svg" width="200" height="120" viewBox="0 0 300 120">
    <defs>
        <!-- Gradient definitions -->
        <linearGradient id="pinkGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#ff8fab;stop-opacity:1"/>
            <stop offset="50%" style="stop-color:#ff4d7a;stop-opacity:1"/>
            <stop offset="100%" style="stop-color:#e91e63;stop-opacity:1"/>
        </linearGradient>

        <linearGradient id="lightPinkGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#fce4ec;stop-opacity:1"/>
            <stop offset="100%" style="stop-color:#f8bbd9;stop-opacity:1"/>
        </linearGradient>

        <linearGradient id="creamGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#fff9c4;stop-opacity:1"/>
            <stop offset="100%" style="stop-color:#fff3e0;stop-opacity:1"/>
        </linearGradient>

        <!-- Drop shadow filter -->
        <filter id="dropshadow" x="-20%" y="-20%" width="140%" height="140%">
            <feDropShadow dx="2" dy="2" stdDeviation="3" flood-color="#ff4d7a" flood-opacity="0.3"/>
        </filter>
    </defs>

    <!-- Background circle -->
    <circle cx="60" cy="60" r="50" fill="url(#lightPinkGradient)" filter="url(#dropshadow)" opacity="0.8"/>

    <!-- Cupcake base -->
    <path d="M35 65 Q35 70 40 70 L80 70 Q85 70 85 65 L82 45 Q82 40 77 40 L43 40 Q38 40 38 45 Z" fill="url(#pinkGradient)" stroke="#e91e63" stroke-width="1"/>

    <!-- Cupcake liner pattern -->
    <rect x="40" y="60" width="4" height="8" fill="#d81b60" opacity="0.6"/>
    <rect x="48" y="60" width="4" height="8" fill="#d81b60" opacity="0.6"/>
    <rect x="56" y="60" width="4" height="8" fill="#d81b60" opacity="0.6"/>
    <rect x="64" y="60" width="4" height="8" fill="#d81b60" opacity="0.6"/>
    <rect x="72" y="60" width="4" height="8" fill="#d81b60" opacity="0.6"/>

    <!-- Cupcake frosting -->
    <path d="M38 45 Q40 30 50 32 Q55 28 60 32 Q65 28 70 32 Q80 30 82 45 Q85 35 75 35 Q70 25 60 30 Q50 25 45 35 Q35 35 38 45 Z" fill="url(#creamGradient)" stroke="#f8bbd9" stroke-width="1"/>

    <!-- Cherry on top -->
    <circle cx="60" cy="35" r="4" fill="#e91e63"/>
    <ellipse cx="58" cy="33" rx="1.5" ry="1" fill="#ff8fab"/>

    <!-- Cherry stem -->
    <path d="M60 31 Q58 28 56 26" stroke="#4caf50" stroke-width="2" fill="none" stroke-linecap="round"/>

    <!-- Sparkles around cupcake -->
    <g fill="#ff4d7a" opacity="0.7">
        <polygon points="25,35 27,39 31,37 27,41 25,45 23,41 19,37 23,39"/>
        <polygon points="90,25 91,27 93,26 91,28 90,30 89,28 87,26 89,27"/>
        <polygon points="95,55 96,57 98,56 96,58 95,60 94,58 92,56 94,57"/>
        <polygon points="20,70 21,72 23,71 21,73 20,75 19,73 17,71 19,72"/>
    </g>

    <!-- Company name -->
    <text x="130" y="45" font-family="Georgia, serif" font-size="24" font-weight="bold" fill="url(#pinkGradient)">
        Sweet
    </text>
    <text x="130" y="70" font-family="Georgia, serif" font-size="24" font-weight="bold" fill="#e91e63">
        Delights
    </text>

    <!-- Decorative underline -->
    <path d="M130 75 Q180 73 230 75" stroke="url(#pinkGradient)" stroke-width="2" fill="none"/>

    <!-- Small decorative elements -->
    <circle cx="235" cy="40" r="2" fill="#ff8fab" opacity="0.8"/>
    <circle cx="240" cy="50" r="1.5" fill="#f8bbd9" opacity="0.8"/>
    <circle cx="245" cy="35" r="1" fill="#ff4d7a" opacity="0.8"/>

    <!-- Tagline -->
    <text x="130" y="90" font-family="Arial, sans-serif" font-size="12" font-style="italic" fill="#e91e63" opacity="0.8">
        Where sweetness meets perfection
    </text>
</svg>
