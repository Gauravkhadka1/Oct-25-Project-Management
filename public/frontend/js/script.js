//    Nav bar JS   Start   //
document.addEventListener('DOMContentLoaded', function() {
    const nav = document.querySelector('nav');
    let lastScrollTop = 0;

    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop) {
            nav.classList.add('hidden');
            nav.classList.remove('scrolled');
        } else {
            nav.classList.remove('hidden');

            // Only add the 'scrolled' class if the navbar is not hidden and scrollTop > 0
            if (scrollTop > 0) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        }

        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // For Mobile or negative scrolling
    });
});

//    Nav bar JS   End   //

//    Scroll Top JS Start    //

let calcScrollValue = () => {
    let scrollProgress = document.getElementById("progress-top");
    let progressValue = document.getElementById("progress-top-value");
    let pos = document.documentElement.scrollTop;
    let calcHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    let scrollValue = Math.round((pos * 100) / calcHeight);

    if (pos > 100) {
        scrollProgress.style.display = "grid";
    } else {
        scrollProgress.style.display = "none";
    }

    scrollProgress.addEventListener("click", () => {
        document.documentElement.scrollTop = 0;
    });

    scrollProgress.style.background = `conic-gradient(#0000ff ${scrollValue}%, #d7d7d7 ${scrollValue}%)`;
};

window.onscroll = calcScrollValue;
window.onload = calcScrollValue;

function showSidebar(){
    const sidebar = document.querySelector('.sidebar')
    sidebar.style.display = 'flex'
}
function hideSidebar(){
    const sidebar = document.querySelector('.sidebar')
    sidebar.style.display = 'none'
}

const pathName = window.location.pathname;
const pageName = pathName.split("/").pop();

if(pageName === "/"){
    document.querySelector("hideOnMobile")
}

let toggles = document.getElementsByClassName('toggle');
let contentDiv = document.getElementsByClassName('faqcontent');
let icons = document.getElementsByClassName('faqicon');

//    Scroll Top JS End    //



// console.log(toggles,contentDiv, icons);

for (let i=0; i<toggles.length; i++){
    toggles[i].addEventListener('click', ()=>{
        if(parseInt(contentDiv[i].style.height)
            != contentDiv[i].scrollHeight){
        contentDiv[i].style.height = contentDiv[i].scrollHeight + "px";
        toggles[i].style.color = "#0084e9";
        icons[i].classList.remove('fa-plus');
        icons[i].classList.add('fa-minus');
    }
    else{
        contentDiv[i].style.height = "0px";
        toggles[i].style.color = "#111130";
        icons[i].classList.remove('fa-minus');
        icons[i].classList.add('fa-plus');
    }
    for (let j=0; j < contentDiv.length; j++){
        if(j !==i){
            contentDiv[j].style.height = "0px";
            toggles[j].style.color = "#111130";
            icons[j].classList.remove('fa-minus');
            icons[j].classList.add('fa-plus');
        }
    }
    });
}
//for about us page Faq
let togglesp = document.getElementsByClassName('togglep');
let contentDivp = document.getElementsByClassName('faqcontentp');
let iconsp = document.getElementsByClassName('faqiconp');

// console.log(toggles,contentDiv, icons);

for (let i=0; i<togglesp.length; i++){
    togglesp[i].addEventListener('click', ()=>{
        if(parseInt(contentDivp[i].style.height)
            != contentDivp[i].scrollHeight){
        contentDivp[i].style.height = contentDivp[i].scrollHeight + "px";
        togglesp[i].style.color = "#0084e9";
        iconsp[i].classList.remove('fa-plus');
        iconsp[i].classList.add('fa-minus');
    }
    else{
        contentDivp[i].style.height = "0px";
        togglesp[i].style.color = "#111130";
        iconsp[i].classList.remove('fa-minus');
        iconsp[i].classList.add('fa-plus');
    }
    for (let j=0; j < contentDivp.length; j++){
        if(j !==i){
            contentDivp[j].style.height = "0px";
            togglesp[j].style.color = "#111130";
            iconsp[j].classList.remove('fa-minus');
            iconsp[j].classList.add('fa-plus');
        }
    }
    });
}


//   Share Button JS  Start   //

const shareBtn1 = document.querySelector(".sharebtn1");
shareBtn1.addEventListener("click", (event)=>{
    if(navigator.share){
        navigator.share({
            title:"Google Official Website",
            url:"https://www.google.com",
        }).then(()=>{
            console.log("Thanks for sharing");
        }).catch((err)=>{
            console.log("Error using webshare API");
            console.log(err);
        })
    }else{
        alert("Browser doesnjofjso")
    }
})

const pshareBtn1 = document.querySelector(".psharebtn1");
pshareBtn1.addEventListener("click", (event)=>{
    if(navigator.share){
        navigator.share({
            title:"Google Official Website",
            url:"https://www.google.com",
        }).then(()=>{
            console.log("Thanks for sharing");
        }).catch((err)=>{
            console.log("Error using webshare API");
            console.log(err);
        })
    }else{
        alert("Browser doesnjofjso")
    }
})
const shareBtn2 = document.querySelector(".sharebtn2");
shareBtn2.addEventListener("click", (event)=>{
    if(navigator.share){
        navigator.share({
            title:"Google Official Website",
            url:"https://www.google.com",
        }).then(()=>{
            console.log("Thanks for sharing");
        }).catch((err)=>{
            console.log("Error using webshare API");
            console.log(err);
        })
    }else{
        alert("Browser doesnjofjso")
    }
})

const pshareBtn2 = document.querySelector(".psharebtn2");
pshareBtn2.addEventListener("click", (event)=>{
    if(navigator.share){
        navigator.share({
            title:"Google Official Website",
            url:"https://www.google.com",
        }).then(()=>{
            console.log("Thanks for sharing");
        }).catch((err)=>{
            console.log("Error using webshare API");
            console.log(err);
        })
    }else{
        alert("Browser doesnjofjso")
    }
})


//   Share Button JS  End   //

// Slider JS   //

