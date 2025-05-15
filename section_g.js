
// !!! SAVE LOCAL DATA
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////


// Key used to save this section's data in localStorage
const storageKey = "form_section_g";

// Form ID
const formId = "language";

// // Load saved data on page load
// window.addEventListener("DOMContentLoaded", () => {
//   const savedData = localStorage.getItem(storageKey);
//   if (savedData) {
//     const formData = JSON.parse(savedData);
//     Object.keys(formData).forEach((key) => {
//       const input = document.getElementById(key);
//       if (input) {
//         input.value = formData[key];
//       }
//     });
//   }
// });

// // Save form data on any input change
// document.getElementById(formId).addEventListener("input", () => {
//   const inputs = document.querySelectorAll(`#${formId} input, #${formId} textarea, #${formId} select`);
//   const data = {};
//   inputs.forEach((input) => {
//     data[input.id] = input.value;
//   });
//   localStorage.setItem(storageKey, JSON.stringify(data));
// });

// // Save data and navigate when "Next" button (as <a>) is clicked
// document.getElementById("nextBtn").addEventListener("click", (e) => {
//   e.preventDefault(); // Prevent the <a> from navigating right away

//   const inputs = document.querySelectorAll(`#${formId} input, #${formId} textarea, #${formId} select`);
//   const data = {};
//   inputs.forEach((input) => {
//     data[input.id] = input.value;
//   });
//   localStorage.setItem(storageKey, JSON.stringify(data));






















// Load saved data on page load
window.addEventListener("DOMContentLoaded", () => {
  const savedData = localStorage.getItem(storageKey);
  if (savedData) {
    const formData = JSON.parse(savedData);
    const inputs = document.querySelectorAll(`#${formId} input, #${formId} textarea, #${formId} select`);
    inputs.forEach((input) => {
      if (input.type === "radio") {
        if (formData[input.name] === input.value) {
          input.checked = true;
        }
      } else {
        input.value = formData[input.id] || "";
      }
    });
  }
});

// Save form data on any input change
document.getElementById(formId).addEventListener("input", () => {
  const inputs = document.querySelectorAll(`#${formId} input, #${formId} textarea, #${formId} select`);
  const data = {};
  inputs.forEach((input) => {
    if (input.type === "radio") {
      if (input.checked) {
        data[input.name] = input.value; // Save radio by group name
      }
    } else {
      data[input.id] = input.value;
    }
  });
  localStorage.setItem(storageKey, JSON.stringify(data));
});

// Save data and navigate when "Next" button is clicked
document.getElementById("nextBtn").addEventListener("click", (e) => {
  e.preventDefault();

  const inputs = document.querySelectorAll(`#${formId} input, #${formId} textarea, #${formId} select`);
  const data = {};
  inputs.forEach((input) => {
    if (input.type === "radio") {
      if (input.checked) {
        data[input.name] = input.value;
      }
    } else {
      data[input.id] = input.value;
    }
  });
  localStorage.setItem(storageKey, JSON.stringify(data));

  // Navigate to the next section
  window.location.href = "section_h.html";
});
