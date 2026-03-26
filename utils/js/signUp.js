let selector = document.querySelector(".selector")
let userMode = document.querySelector(".user-mode")
let teacherMode = document.querySelector(".teacher-mode")
let mode = document.querySelector("#mode")
userMode.addEventListener("click",()=>{
    selector.style.left = "0%"
    mode.value = "user"
    
})
teacherMode.addEventListener("click",()=>{
    selector.style.left = "50%"
    mode.value = "teacher"
})