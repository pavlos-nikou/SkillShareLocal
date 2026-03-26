const categoryScores = {
    "Sustainable Living": 3,
    "Energy & Renewable Solutions": 5,
    "Gardening & Composting": 4,
    "DIY & Repair": 2,
    "Water Conservation": 5,
    "Eco-Friendly Technology": 3
};

const score = document.querySelector(".impactScore")
console.log(score);

const cat = document.querySelector(".category").textContent
console.log(categoryScores[cat]);
score.textContent = score.textContent * categoryScores[cat]
