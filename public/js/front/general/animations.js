// fade in left
const fadeInLeft = {
  delay: 200,
  useDelay: 'onload',
  distance: '50px',
  duration: 800,
  origin: 'left',
  interval: 200,
  easing: 'ease-out'
}

ScrollReveal().reveal('[data-anim-fade-in-left]', fadeInLeft);

// fade in right
const fadeInRight = {
  delay: 200,
  useDelay: 'onload',
  distance: '50px',
  duration: 800,
  origin: 'right',
  interval: 200,
  easing: 'ease-out'
}
ScrollReveal().reveal('[data-anim-fade-in-right]', fadeInRight);

// fade in up
const fadeInUp = {
  delay: 200,
  useDelay: 'onload',
  distance: '50px',
  duration: 800,
  origin: 'bottom',
  interval: 200,
  easing: 'ease-out'
}
ScrollReveal().reveal('[data-anim-fade-in-up]', fadeInUp);

// scale in
const scaleIn = {
  delay: 200,
  useDelay: 'onload',
  duration: 900,
  scale: 0.8,
  interval: 200,
  easing: 'ease-out'
}

ScrollReveal().reveal('[data-anim-scale-in]', scaleIn);