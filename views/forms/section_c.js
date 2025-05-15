
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


// // Key used to save this section's data in localStorage
// const storageKey = "form_section_c";

// // Form ID
// const formId = "nominated_third_party";

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























// // Key used to save this section's data in localStorage
// const storageKey = "form_section_c";

// // Form ID
// const formId = "nominated_third_party";

// // ✅ Load saved data on page load
// document.addEventListener("DOMContentLoaded", () => {
//   const savedData = localStorage.getItem(storageKey);
//   if (savedData) {
//     const formData = JSON.parse(savedData);
//     const inputs = document.querySelectorAll(`#${formId} input, #${formId} textarea, #${formId} select`);
//     inputs.forEach((input) => {
//       if (input.type === "checkbox") {
//         input.checked = formData[input.id] || false;
//       } else if (input.type === "radio") {
//         if (formData[input.name] === input.value) {
//           input.checked = true;
//         }
//       } else {
//         input.value = formData[input.id] || "";
//       }
//     });
//   }
//   console.log(document.getElementById("thirdPartyYes"))
//   document.getElementById("thirdPartyYes").addEventListener("click", (event) => {
//     const data = event.target.value;
//     localStorage.setItem(storageKey, JSON.stringify(data));
//   });
  
//   document.getElementById("thirdPartyNo").addEventListener("click", (event) => {
//     const data = event.target.value;
//     localStorage.setItem(storageKey, JSON.stringify(data));
//   });
  
//   // ✅ Save data and navigate on "Next" button click (using <a>)
//   document.getElementById("nextBtn").addEventListener("click", (e) => {
//     e.preventDefault();
  
//     const inputs = document.querySelectorAll(`#${formId} input, #${formId} textarea, #${formId} select`);
//     const data = {};
  
//     inputs.forEach((input) => {
//       if (input.type === "radio") {
//         if (input.checked) {
//           data[input.name] = input.value;
//         }
//       } else if (input.type === "checkbox") {
//         data[input.id] = input.checked;
//       } else {
//         data[input.id] = input.value;
//       }
//     });
  
//     localStorage.setItem(storageKey, JSON.stringify(data));
  
//     // Navigate to the next section
//     window.location.href = "section_d.html";
//   });
  
// });































const storageKey = "form_section_c";
const formId = "nominated_third_party";

// ✅ Load saved data on page load
document.addEventListener("DOMContentLoaded", () => {
  const savedData = localStorage.getItem(storageKey);
  if (savedData) {
    const formData = JSON.parse(savedData);
    const inputs = document.querySelectorAll(`#${formId} input, #${formId} textarea, #${formId} select`);
    inputs.forEach((input) => {
      if (input.type === "checkbox") {
        input.checked = formData[input.id] || false;
      } else if (input.type === "radio") {
        if (formData[input.name] === input.value) {
          input.checked = true;
        }
      } else {
        input.value = formData[input.id] || "";
      }
    });
  }

  // ✅ Save data on input change
  document.getElementById(formId).addEventListener("input", () => {
    const inputs = document.querySelectorAll(`#${formId} input, #${formId} textarea, #${formId} select`);
    const data = {};

    inputs.forEach((input) => {
      if (input.type === "radio") {
        if (input.checked) {
          data[input.name] = input.value;
        }
      } else if (input.type === "checkbox") {
        data[input.id] = input.checked;
      } else {
        data[input.id] = input.value;
      }
    });

    localStorage.setItem(storageKey, JSON.stringify(data));
  });

  // ✅ Save data and go to next page
  document.getElementById("nextBtn").addEventListener("click", (e) => {
    e.preventDefault();
    const inputs = document.querySelectorAll(`#${formId} input, #${formId} textarea, #${formId} select`);
    const data = {};

    inputs.forEach((input) => {
      if (input.type === "radio") {
        if (input.checked) {
          data[input.name] = input.value;
        }
      } else if (input.type === "checkbox") {
        data[input.id] = input.checked;
      } else {
        data[input.id] = input.value;
      }
    });

    localStorage.setItem(storageKey, JSON.stringify(data));
    window.location.href = "section_d.html";
  });
});
