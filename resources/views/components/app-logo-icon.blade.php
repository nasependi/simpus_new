<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 42" {{ $attributes }} role="img" aria-label="Logo Puskesmas">
    <!-- Hexagon outer (stroke) with white fill inside -->
    <polygon points="20,2 36,11 36,31 20,40 4,31 4,11" fill="#ffffff" stroke="#0b5f2a" stroke-width="1.6" />

    <!-- Green cross (medical) centered -->
    <!-- vertical arm -->
    <rect x="16.5" y="6.5" width="7" height="29" rx="0.6" fill="#0b5f2a" />
    <!-- horizontal arm -->
    <rect x="6" y="15.5" width="28" height="7" rx="0.6" fill="#0b5f2a" />

    <!-- House/roof shape (sits on right side of cross) -->
    <!-- lighter green roof + small white stroke edge to match reference -->
    <path d="M14.5 20.5 L20 16.2 L27.8 20.5 L27.8 24.2 L19.9 24.2 L19.9 33.5 L14.5 33.5 Z"
        fill="#1f8f3e" stroke="#ffffff" stroke-width="0.9" stroke-linejoin="round" />

    <!-- Two interlocking rings inside roof (white stroke, no fill) -->
    <g transform="translate(20.2,22.2)">
        <circle cx="-0.8" cy="-0.2" r="1.6" fill="none" stroke="#ffffff" stroke-width="0.9" />
        <circle cx="1.2" cy="-0.2" r="1.6" fill="none" stroke="#ffffff" stroke-width="0.9" />
    </g>
</svg>