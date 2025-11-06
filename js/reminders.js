const faqs = document.querySelectorAll(".faq-item");

faqs.forEach((item) => {
  const question = item.querySelector(".faq-question");

  question.addEventListener("click", () => {
    // Close other open FAQs
    faqs.forEach((faq) => {
      if (faq !== item) faq.classList.remove("active");
    });

    // Toggle the current one
    item.classList.toggle("active");
  });
});
