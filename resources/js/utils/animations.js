import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

export function initAnimations() {
  heroAnimation()
  fadeUpAnimation()
  fadeDirectionAnimation('fade-left', -60)
  fadeDirectionAnimation('fade-right', 60)
  staggerAnimation()
  countUpAnimation()
  ScrollTrigger.refresh()
}

export function refreshScrollTrigger() {
  ScrollTrigger.refresh()
}

function heroAnimation() {
  const hero = document.querySelector('[data-gsap-hero]')
  if (!hero) return

  const items = hero.querySelectorAll('[data-gsap-hero-item]')
  if (!items.length) return

  const tl = gsap.timeline({ delay: 0.3 })
  tl.from(items, {
    y: 80,
    opacity: 0,
    stagger: 0.2,
    duration: 1,
    ease: 'power4.out',
  })
}

function fadeUpAnimation() {
  gsap.utils.toArray('[data-gsap="fade-up"]').forEach((el) => {
    gsap.from(el, {
      y: 60,
      opacity: 0,
      duration: 0.8,
      ease: 'power3.out',
      scrollTrigger: {
        trigger: el,
        start: 'top 85%',
        once: true,
      },
    })
  })
}

function fadeDirectionAnimation(attr, xAmount) {
  gsap.utils.toArray(`[data-gsap="${attr}"]`).forEach((el) => {
    gsap.from(el, {
      x: xAmount,
      opacity: 0,
      duration: 0.8,
      ease: 'power3.out',
      scrollTrigger: {
        trigger: el,
        start: 'top 85%',
        once: true,
      },
    })
  })
}

function staggerAnimation() {
  gsap.utils.toArray('[data-gsap="stagger"]').forEach((el) => {
    gsap.from(el.children, {
      y: 40,
      opacity: 0,
      stagger: 0.1,
      duration: 0.6,
      ease: 'power2.out',
      scrollTrigger: {
        trigger: el,
        start: 'top 85%',
        once: true,
      },
    })
  })
}

function countUpAnimation() {
  gsap.utils.toArray('[data-gsap="count"]').forEach((el) => {
    const raw = el.dataset.target || el.textContent.replace(/[.,\s]/g, '')
    const target = parseFloat(raw)
    if (isNaN(target) || target === 0) return

    ScrollTrigger.create({
      trigger: el,
      start: 'top 85%',
      once: true,
      onEnter: () => {
        const obj = { val: 0 }
        gsap.to(obj, {
          val: target,
          duration: 2,
          ease: 'power2.out',
          onUpdate: () => {
            el.textContent = Math.round(obj.val).toLocaleString('id-ID')
          },
        })
      },
    })
  })
}
