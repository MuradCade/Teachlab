document.addEventListener("DOMContentLoaded", () => {
    // Theme toggle functionality
    const themeToggle = document.querySelector(".theme-toggle")
    const body = document.body
  
    // Check for saved theme preference or use preferred color scheme
    const savedTheme = localStorage.getItem("theme")
    if (savedTheme === "dark" || (!savedTheme && window.matchMedia("(prefers-color-scheme: dark)").matches)) {
      body.classList.add("dark-mode")
    }
  
    themeToggle.addEventListener("click", () => {
      body.classList.toggle("dark-mode")
      // Save theme preference
      localStorage.setItem("theme", body.classList.contains("dark-mode") ? "dark" : "light")
    })
  
    // Mobile menu toggle
    const menuToggle = document.querySelector(".mobile-menu-toggle")
    const mobileMenu = document.querySelector(".mobile-menu")
  
    menuToggle.addEventListener("click", () => {
      mobileMenu.classList.toggle("active")
      // Animate hamburger to X
      const spans = menuToggle.querySelectorAll("span")
      spans.forEach((span) => span.classList.toggle("active"))
  
      if (mobileMenu.classList.contains("active")) {
        spans[0].style.transform = "rotate(45deg) translate(5px, 5px)"
        spans[1].style.opacity = "0"
        spans[2].style.transform = "rotate(-45deg) translate(5px, -5px)"
      } else {
        spans[0].style.transform = "none"
        spans[1].style.opacity = "1"
        spans[2].style.transform = "none"
      }
    })
  
    // Close mobile menu when clicking outside
    document.addEventListener("click", (event) => {
      if (
        !event.target.closest(".mobile-menu") &&
        !event.target.closest(".mobile-menu-toggle") &&
        mobileMenu.classList.contains("active")
      ) {
        mobileMenu.classList.remove("active")
        const spans = menuToggle.querySelectorAll("span")
        spans[0].style.transform = "none"
        spans[1].style.opacity = "1"
        spans[2].style.transform = "none"
      }
    })
  
    // Testimonial slider functionality
    const dots = document.querySelectorAll(".dot")
    const testimonialSlider = document.querySelector(".testimonials-slider")
    const testimonialCards = document.querySelectorAll(".testimonial-card")
  
    dots.forEach((dot, index) => {
      dot.addEventListener("click", () => {
        // Update active dot
        dots.forEach((d) => d.classList.remove("active"))
        dot.classList.add("active")
  
        // Scroll to the corresponding testimonial
        const cardWidth = testimonialCards[0].offsetWidth + 20 // card width + gap
        testimonialSlider.scrollTo({
          left: index * cardWidth,
          behavior: "smooth",
        })
      })
    })
  
    // Create dashboard image placeholder
    createDashboardPlaceholder()
  
    // Video placeholder click handler
    const videoPlaceholder = document.querySelector(".video-placeholder")
    if (videoPlaceholder) {
      videoPlaceholder.addEventListener("click", () => {
        // In a real implementation, this would play a video
        alert("Video would play here in a real implementation")
      })
    }
  })
  
  function createDashboardPlaceholder() {
    // This function creates a placeholder for the dashboard image
    // In a real implementation, you would use an actual image
    const canvas = document.createElement("canvas")
    const ctx = canvas.getContext("2d")
    const dashboardContainer = document.querySelector(".dashboard-preview")
  
    // If no dashboard container is found, exit the function
    if (!dashboardContainer) return
  
    // Remove any existing image
    const existingImg = dashboardContainer.querySelector("img")
    if (existingImg) {
      existingImg.remove()
    }
  
    // Set canvas dimensions
    canvas.width = 1000
    canvas.height = 600
  
    // Draw dashboard background
    ctx.fillStyle = "#FFFFFF"
    ctx.fillRect(0, 0, canvas.width, canvas.height)
  
    // Draw sidebar
    ctx.fillStyle = "#F9FAFB"
    ctx.fillRect(0, 0, 200, canvas.height)
  
    // Draw header
    ctx.fillStyle = "#FFFFFF"
    ctx.fillRect(200, 0, canvas.width - 200, 60)
    ctx.strokeStyle = "#E5E7EB"
    ctx.lineWidth = 1
    ctx.beginPath()
    ctx.moveTo(200, 60)
    ctx.lineTo(canvas.width, 60)
    ctx.stroke()
  
    // Draw logo in sidebar
    ctx.fillStyle = "#10B981"
    ctx.beginPath()
    ctx.arc(40, 40, 15, 0, Math.PI * 2)
    ctx.fill()
  
    // Draw menu items in sidebar
    for (let i = 0; i < 5; i++) {
      ctx.fillStyle = i === 0 ? "#F3F4F6" : "transparent"
      ctx.fillRect(10, 80 + i * 40, 180, 36)
  
      ctx.fillStyle = i === 0 ? "#10B981" : "#6B7280"
      ctx.fillRect(10, 80 + i * 40, 4, 36)
  
      ctx.fillStyle = i === 0 ? "#111827" : "#6B7280"
      ctx.font = "14px Inter"
      ctx.fillText(["Dashboard", "Tasks", "Calendar", "Analytics", "Team"][i], 50, 102 + i * 40)
    }
  
    // Draw dashboard content
    ctx.fillStyle = "#111827"
    ctx.font = "bold 24px Inter"
    ctx.fillText("Dashboard", 230, 100)
  
    ctx.fillStyle = "#6B7280"
    ctx.font = "14px Inter"
    ctx.fillText("Plan, prioritize, and accomplish your tasks with ease.", 230, 130)
  
    // Draw stats cards
    const cardColors = ["#10B981", "#3B82F6", "#8B5CF6", "#F59E0B"]
    const cardTitles = ["Total Projects", "Ended Projects", "Running Projects", "Pending Project"]
    const cardValues = ["24", "10", "12", "2"]
  
    for (let i = 0; i < 4; i++) {
      const x = 230 + (i % 4) * 190
      const y = 160
  
      // Card background
      ctx.fillStyle = i === 0 ? cardColors[i] : "#FFFFFF"
      ctx.beginPath()
      ctx.roundRect(x, y, 170, 100, 8)
      ctx.fill()
  
      // Card border
      if (i > 0) {
        ctx.strokeStyle = "#E5E7EB"
        ctx.lineWidth = 1
        ctx.stroke()
      }
  
      // Card content
      ctx.fillStyle = i === 0 ? "#FFFFFF" : "#6B7280"
      ctx.font = "14px Inter"
      ctx.fillText(cardTitles[i], x + 15, y + 30)
  
      ctx.fillStyle = i === 0 ? "#FFFFFF" : "#111827"
      ctx.font = "bold 36px Inter"
      ctx.fillText(cardValues[i], x + 15, y + 70)
    }
  
    // Draw charts section
    ctx.fillStyle = "#111827"
    ctx.font = "bold 18px Inter"
    ctx.fillText("Project Analytics", 230, 300)
  
    // Draw bar chart
    const barColors = ["#D1D5DB", "#10B981", "#34D399", "#064E3B", "#D1D5DB", "#D1D5DB", "#D1D5DB"]
    for (let i = 0; i < 7; i++) {
      const barHeight = 30 + Math.random() * 70
      ctx.fillStyle = barColors[i]
      ctx.fillRect(230 + i * 40, 330 + (100 - barHeight), 30, barHeight)
  
      ctx.fillStyle = "#6B7280"
      ctx.font = "12px Inter"
      ctx.fillText(["M", "T", "W", "T", "F", "S", "S"][i], 240 + i * 40, 445)
    }
  
    // Draw reminders section
    ctx.fillStyle = "#111827"
    ctx.font = "bold 18px Inter"
    ctx.fillText("Reminders", 550, 300)
  
    // Draw reminder card
    ctx.fillStyle = "#FFFFFF"
    ctx.beginPath()
    ctx.roundRect(550, 320, 220, 100, 8)
    ctx.fill()
    ctx.strokeStyle = "#E5E7EB"
    ctx.lineWidth = 1
    ctx.stroke()
  
    ctx.fillStyle = "#111827"
    ctx.font = "bold 14px Inter"
    ctx.fillText("Meeting with Acme Company", 570, 350)
  
    ctx.fillStyle = "#6B7280"
    ctx.font = "12px Inter"
    ctx.fillText("Time: 10:00 am - 11:00 pm", 570, 370)
  
    // Draw button
    ctx.fillStyle = "#10B981"
    ctx.beginPath()
    ctx.roundRect(570, 390, 100, 20, 4)
    ctx.fill()
  
    ctx.fillStyle = "#FFFFFF"
    ctx.font = "12px Inter"
    ctx.fillText("Start Meeting", 585, 405)
  
    // Convert canvas to image
    const dataUrl = canvas.toDataURL("image/png")
    const img = new Image()
    img.src = dataUrl
    img.alt = "Donezo Dashboard Interface"
    img.className = "dashboard-image"
  
    // Add to the DOM
    dashboardContainer.appendChild(img)
  }
  
  // WhatsApp popup functionality
  // ... existing code ...
document.addEventListener('DOMContentLoaded', function() {
  const whatsappIcon = document.getElementById('whatsappIcon');
  const whatsappPopup = document.getElementById('whatsappPopup');
  const closePopup = document.getElementById('closePopup');

  // Toggle popup when WhatsApp icon is clicked
  whatsappIcon.addEventListener('click', function(e) {
      e.stopPropagation(); // Prevent event from bubbling up
      whatsappPopup.classList.toggle('show');
  });

  // Close popup when close button is clicked
  closePopup.addEventListener('click', function(e) {
      e.stopPropagation(); // Prevent event from bubbling up
      whatsappPopup.classList.remove('show');
  });

  // Close popup when clicking outside
  document.addEventListener('click', function(e) {
      if (!whatsappPopup.contains(e.target) && e.target !== whatsappIcon) {
          whatsappPopup.classList.remove('show');
      }
  });
});
// ... existing code ...