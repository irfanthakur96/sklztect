/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: ["class"],
  content: [
    "src/modules/cpt-builder/components/**/*.{js,jsx}",
    "src/modules/cpt-builder/layouts/**/*.{js,jsx}",
    "src/modules/cpt-builder/pages/**/*.{js,jsx}",
    "src/modules/cpt-builder/GetStartCPT.jsx",
  ],
  prefix: "",
  important: ".wcfcb2025",
  corePlugins: {
    preflight: false,
    container: false,
  },
  theme: {
  	container: {
  		center: 'true',
  		screens: {
  			'2xl': '1440px'
  		}
  	},
  	extend: {
  		fontFamily: {
  			inter: [
  				'Inter',
  				'sans-serif'
  			]
  		},
  		colors: {
  			brand: {
  				DEFAULT: 'hsl(var(--brand-500))',
  				secondary: 'hsl(var(--brand-600))'
  			},
  			background: {
  				DEFAULT: 'hsl(var(--gray-00))',
  				secondary: 'hsl(var(--gray-50))'
  			},
  			text: {
  				DEFAULT: 'hsl(var(--gray-900))',
  				hover: 'hsl(var(--brand-500))',
  				secondary: 'hsl(var(--gray-600))',
  				'secondary-hover': 'hsl(var(--gray-900))'
  			},
  			label: {
  				DEFAULT: 'hsl(var(--gray-500))',
  				hover: 'hsl(var(--brand-900))'
  			},
  			icon: {
  				DEFAULT: 'hsl(var(--gray-600))',
  				hover: 'hsl(var(--gray-900))',
  				active: 'hsl(var(--brand-500))',
  				secondary: 'hsl(var(--gray-500))'
  			},
  			button: {
  				DEFAULT: 'hsl(var(--brand-500))',
  				hover: 'hsl(var(--brand-600))',
  				secondary: 'hsl(var(--gray-00))',
  				'secondary-hover': 'hsl(var(--gray-50))'
  			},
  			'button-text': {
  				DEFAULT: 'hsl(var(--gray-00))',
  				hover: 'hsl(var(--gray-00))',
  				secondary: 'hsl(var(--gray-600))',
  				'secondary-hover': 'hsl(var(--gray-900))'
  			},
  			border: {
  				DEFAULT: 'hsl(var(--gray-200))',
  				secondary: 'hsl(var(--gray-100))'
  			}
  		},
  		boxShadow: {
  			pro: '0px 4px 12px 0px rgba(10, 13, 20, 0.06)',
  			common: '0px 1px 4px 0px rgba(10, 13, 20, 0.03)',
  			'common-2': '0px 1px 2px 0px rgba(10, 13, 20, 0.03)',
  			select: '0px 6px 16px 0px rgba(0, 0, 0, 0.04), 0px 1px 4px 0px rgba(10, 13, 20, 0.06)',
  			'tab-trigger': '0px 2px 4px 0px rgba(14, 18, 27, 0.03), 0px 6px 10px 0px rgba(14, 18, 27, 0.06)',
  			'widget-card': '0px 0px 0px 3px rgba(252, 104, 72, 0.25), 0px 1px 2px 0px rgba(10, 13, 20, 0.03)',
  			'template-card': '0px 12px 12px 0px rgba(10, 13, 20, 0.02), 0px 1px 2px 0px rgba(10, 13, 20, 0.04), 0px 0px 1px 0px rgba(10, 13, 20, 0.08)',
  			'general-btn': '0px 2px 6px 0px rgba(10, 13, 20, 0.04), 0px 6px 16px 0px rgba(10, 13, 20, 0.12), 0px 0px 0px 2px rgba(10, 13, 20, 0.08)',
  			'auth-btn': '0px 0px 11px 1px rgba(255, 255, 255, 0.15) inset',
  			'auth-btn-2': '0px 0px 0px 4px rgba(0, 0, 0, 0.06), 0px 1px 2px 0px rgba(10, 13, 20, 0.03)',
  			'auth-card': '0px 16px 32px -10px rgba(10, 13, 20, 0.10)'
  		},
  		keyframes: {
  			'accordion-down': {
  				from: {
  					height: '0'
  				},
  				to: {
  					height: 'var(--radix-accordion-content-height)'
  				}
  			},
  			'accordion-up': {
  				from: {
  					height: 'var(--radix-accordion-content-height)'
  				},
  				to: {
  					height: '0'
  				}
  			},
  			'accordion-down': {
  				from: {
  					height: '0'
  				},
  				to: {
  					height: 'var(--radix-accordion-content-height)'
  				}
  			},
  			'accordion-up': {
  				from: {
  					height: 'var(--radix-accordion-content-height)'
  				},
  				to: {
  					height: '0'
  				}
  			}
  		},
  		animation: {
  			'accordion-down': 'accordion-down 0.2s ease-out',
  			'accordion-up': 'accordion-up 0.2s ease-out',
  			'accordion-down': 'accordion-down 0.2s ease-out',
  			'accordion-up': 'accordion-up 0.2s ease-out'
  		}
  	}
  },
  plugins: [
    require("tailwindcss-animate"),
    function ({ addUtilities }) {
      addUtilities({
        ".no-spinner": {
          "&::-webkit-inner-spin-button": { appearance: "none", margin: "0" },
          "&::-webkit-outer-spin-button": { appearance: "none", margin: "0" },
          "&": { MozAppearance: "textfield", appearance: "textfield" },
        },
      });
    },
  ],
};
