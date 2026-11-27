tailwind.config = {
      theme: {
        extend: {
          colors: {
            'beige': '#f5f1e8',
            'beige2': '#efe9dc',
            'cream': '#fbfaf7',
            'paper': 'rgba(255,255,255,.90)',
            'ink': '#1f2023',
            'ink2': '#2a2a27',
            'muted-ink': 'rgba(31,32,35,.75)',
            'gold': '#c9a24a',
            'gold2': '#e6c98b',
            'line-gold': 'rgba(201,162,74,.35)',
            'shadow-ink': 'rgba(31,32,35,.12)'
          },
          fontFamily: {
            playfair: ['Playfair Display', 'serif'],
            inter: ['Inter', 'system-ui', 'sans-serif'],
          },
          boxShadow: {
            'soft': '0 14px 45px rgba(31,32,35,.08)',
            'glow': '0 0 0 1px rgba(201,162,74,.25), 0 20px 60px rgba(31,32,35,.09)',
            'glow-strong': '0 0 28px rgba(201,162,74,.15), 0 20px 60px rgba(31,32,35,.12)',
            'enterprise': '0 24px 80px rgba(31,32,35,.10), 0 8px 24px rgba(31,32,35,.06)',
          },
          animation: {
            'fade-in': 'fadeIn 0.8s ease-out',
            'fade-in-up': 'fadeInUp 0.8s ease-out',
            'fade-in-down': 'fadeInDown 0.8s ease-out',
            'slide-in-left': 'slideInLeft 0.8s ease-out',
            'slide-in-right': 'slideInRight 0.8s ease-out',
            'scale-in': 'scaleIn 0.6s ease-out',
            'pulse-glow': 'pulseGlow 3s ease-in-out infinite',
          }
        }
      }
    }