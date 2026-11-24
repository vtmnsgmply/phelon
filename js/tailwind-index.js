tailwind.config = {
      theme: {
        extend: {
          colors: {
            // Light base
            'beige': '#f5f1e8',
            'beige2': '#efe9dc',
            'cream': '#fbfaf7',
            'paper': 'rgba(255,255,255,.86)',

            // Text
            'ink': '#1f2023',
            'ink2': '#2a2a27',
            'muted-ink': 'rgba(31,32,35,.75)',

            // Accents
            'gold': '#c9a24a',
            'gold2': '#e6c98b',
            'line-gold': 'rgba(201,162,74,.35)',

            // Utility
            'shadow-ink': 'rgba(31,32,35,.12)'
          },
          fontFamily: {
            playfair: ['Playfair Display', 'serif'],
            inter: ['Inter', 'system-ui', 'sans-serif'],
          },
          boxShadow: {
            soft: '0 14px 45px rgba(31,32,35,.10)',
            glow: '0 0 0 1px rgba(201,162,74,.28), 0 18px 55px rgba(31,32,35,.10)',
            'glow-strong': '0 0 22px rgba(201,162,74,.18), 0 18px 55px rgba(31,32,35,.14)',
          }
        }
      }
    }