const showPassword = document.querySelector ("#show-password");
const passwordField = document.querySelector ("#password");

showPassword.addEventListener("click", function(){
    this.classList.toggle("fa-eye");
    this.classList.toggle("fa-eye-slash",!
        this.classList.contains("fa-eye"));
    
    const type = passwordField.getAttribute("type") === "password" ? "text" : "password"; passwordField.setAttribute("type", type);
})