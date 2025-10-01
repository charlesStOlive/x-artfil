import Alpine from 'alpinejs'

// Démarrer Alpine
window.Alpine = Alpine

// Fonction principale de l'application front-end
window.frontApp = () => {
    return {
        // État de l'application
        loading: false,
        notifications: [],
        
        // Initialisation
        init() {
            console.log('Front App initialized')
            this.initAnimations()
        },

        // Gestion du thème (mode sombre retiré)
        initTheme() {
            // Plus de gestion du mode sombre
            console.log('Theme initialized (light mode only)')
        },

        // Animations au scroll
        initAnimations() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in')
                    }
                })
            }, observerOptions)

            // Observer tous les éléments avec la classe 'observe-me'
            document.querySelectorAll('.observe-me').forEach(el => {
                observer.observe(el)
            })
        },

        // Notifications
        addNotification(message, type = 'success', duration = 5000) {
            const id = Date.now()
            const notification = {
                id,
                message,
                type,
                show: true
            }
            
            this.notifications.push(notification)
            
            // Auto-suppression après duration
            setTimeout(() => {
                this.removeNotification(id)
            }, duration)
        },

        removeNotification(id) {
            const index = this.notifications.findIndex(n => n.id === id)
            if (index > -1) {
                this.notifications[index].show = false
                setTimeout(() => {
                    this.notifications.splice(index, 1)
                }, 300) // Attendre la fin de l'animation
            }
        },

        // Utilitaires
        formatDate(date) {
            return new Intl.DateTimeFormat('fr-FR', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }).format(new Date(date))
        },

        formatCurrency(amount) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR'
            }).format(amount)
        },

        // Formulaires
        async submitForm(formData, url, method = 'POST') {
            this.loading = true
            
            try {
                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(formData)
                })

                const result = await response.json()
                
                if (response.ok) {
                    this.addNotification(result.message || 'Opération réussie', 'success')
                    return result
                } else {
                    throw new Error(result.message || 'Une erreur est survenue')
                }
            } catch (error) {
                this.addNotification(error.message, 'error')
                throw error
            } finally {
                this.loading = false
            }
        },

        // Scroll fluide vers un élément
        scrollTo(elementId) {
            const element = document.getElementById(elementId)
            if (element) {
                element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                })
            }
        },

        // Copier dans le presse-papier
        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text)
                this.addNotification('Copié dans le presse-papier', 'success', 2000)
            } catch (error) {
                this.addNotification('Erreur lors de la copie', 'error', 2000)
            }
        }
    }
}

// Composant pour les modales
Alpine.data('modal', () => ({
    show: false,
    
    open() {
        this.show = true
        document.body.style.overflow = 'hidden'
    },
    
    close() {
        this.show = false
        document.body.style.overflow = ''
    },
    
    closeOnEscape(event) {
        if (event.key === 'Escape') {
            this.close()
        }
    }
}))

// Composant pour les accordéons
Alpine.data('accordion', () => ({
    open: false,
    
    toggle() {
        this.open = !this.open
    }
}))

// Composant pour les onglets
Alpine.data('tabs', (defaultTab = 0) => ({
    activeTab: defaultTab,
    
    setActiveTab(index) {
        this.activeTab = index
    }
}))

// Composant pour le carousel
Alpine.data('carousel', (items = []) => ({
    items,
    currentIndex: 0,
    
    next() {
        this.currentIndex = (this.currentIndex + 1) % this.items.length
    },
    
    prev() {
        this.currentIndex = this.currentIndex === 0 ? this.items.length - 1 : this.currentIndex - 1
    },
    
    goTo(index) {
        this.currentIndex = index
    },
    
    // Auto-play
    startAutoPlay(interval = 5000) {
        setInterval(() => {
            this.next()
        }, interval)
    }
}))

// Composant pour les tooltips
Alpine.data('tooltip', (text) => ({
    show: false,
    text,
    
    showTooltip() {
        this.show = true
    },
    
    hideTooltip() {
        this.show = false
    }
}))

// Utilitaires globaux
window.utils = {
    // Debounce function
    debounce(func, wait) {
        let timeout
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout)
                func(...args)
            }
            clearTimeout(timeout)
            timeout = setTimeout(later, wait)
        }
    },

    // Throttle function
    throttle(func, limit) {
        let inThrottle
        return function() {
            const args = arguments
            const context = this
            if (!inThrottle) {
                func.apply(context, args)
                inThrottle = true
                setTimeout(() => inThrottle = false, limit)
            }
        }
    },

    // Générer un ID unique
    generateId() {
        return '_' + Math.random().toString(36).substr(2, 9)
    },

    // Validation d'email
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        return emailRegex.test(email)
    },

    // Validation de téléphone français
    isValidPhoneFR(phone) {
        const phoneRegex = /^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/
        return phoneRegex.test(phone)
    },

    // Formater un numéro de téléphone
    formatPhoneFR(phone) {
        const cleaned = phone.replace(/\D/g, '')
        const match = cleaned.match(/^(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})$/)
        if (match) {
            return match.slice(1).join(' ')
        }
        return phone
    }
}

// Démarrer Alpine
Alpine.start()