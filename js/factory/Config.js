module.exports = function(){
    return {
        nom: "Mutlti-Modules APP",
        version: "1.0",
        paths:
        {
            "/": [{caption: "Accueil", href: "/"}],
            "/about": [{caption: "A propos", href: "about"}],
            "/login": [{caption: "Se connecter", href: "login"}]
        }
    }
};