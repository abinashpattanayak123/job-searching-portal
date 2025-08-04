var tl = gsap.timeline()
tl.from("nav a,nav ul,nav button,nav img",{
    y:-100,
    duration:1,
    // ease: "power4.out",
    delay:1,
    opacity:0,
    stagger: 0.1
})

tl.from(".left h1,.left p",{
    y:100,
    opacity:0,
    stagger:0.2
})
tl.from(".right img",{
    y:200,
    x:400,
    opacity:0,
    stagger:0.2
})
tl.from(".about,.para h2,.para p",{
    y:100,
    opacity:0,
    stagger:0.2
})
