
<!-- Buttons to filter applications based on status -->
<!-- Dropdown to filter applications by status -->
<div class="mb-3 d-flex">
  <div class="d-flex align-items-center gap-2">
    <label for="statusFilter" class="form-label m-0 fs-5 fw-bold">Show:</label>
    <select id="statusFilter" class="form-select bg-white" onchange="filterApplications(this.value)">
        <option value="all">All</option>
        <option value="waitlist">Waitlist</option>
        <option value="approved">Approved</option>
        <option value="rejected">Rejected</option>
    </select>
  </div>
</div>

<div id="assignModal" class="modal show" tabindex="-1" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1050; background-color: rgba(0, 0, 0, 0.5);">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-white">
      <div class="modal-header">
        <h5 class="modal-title">Assign Reviewers</h5>
        <button type="button" class="btn-close" onclick="closeAssignModal()"></button>
      </div>
      <div class="modal-body">
        <form id="reviewerForm">
          <div id="reviewerList" class="list-group"></div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn bg-primary color-white" onclick="submitReviewerAssignments()">Assign Selected</button>
      </div>
    </div>
  </div>
</div>


<div id="app-container"></div>

<script type="module">
import { getAllApplications, deleteApplication, editApplication, getAllReviewers, assignReviewersToApp } from '../../controllers/admin-controller.js';

function renderAppCard(app){
    const collapseId = "app" + app.id;
    return `
    <div class="card mb-2 shadow-sm rounded app-card" app-status="${app.status}" data-app-id="${app.id}">
        <div class="content-item bg-white p-4 d-flex align-items-center justify-content-between w-100">
            <div class="d-flex align-items-center gap-3">
                <i class="fa-regular fa-circleApplication-2x"></i>
                <span>${app.applicant_name}</span>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button class="btn toggle-btn" data-bs-toggle="collapse" data-bs-target="#${collapseId}">
                    <i class="fa fa-caret-down"></i> 
                </button>
            </div>
        </div>

        <!-- collapsable dropdown section -->
        <div class="collapse bg-secondary" id="${collapseId}">
            <div class="card-body border-top d-flex justify-content-end px-5">
                <!-- Application Info -->
                <div class="content-info d-flex flex-column gap-2">
                    <strong>Application Info</strong>
                    <div>${formatDate(app.created_at)}</div>
                    <input type="text" class="form-control form-control-sm editable-programme bg-white" value="${app.programme}" readonly>
                    <input type="text" class="form-control form-control-sm editable-status bg-white" value="${app.status}" readonly>
                </div>
                <!-- Reviewer Info -->
                <div class="content-info d-flex flex-column gap-2">
                    <strong>Reviewer</strong>
                   ${
                    app.reviewer_names && app.reviewer_names.length > 0
                        ? app.reviewer_names.map(r => `<div>${r}</div>`).join('')
                        : '<div>No reviewers</div>'
                    }
                </div>
            </div>
            <div class="d-flex align-items-center gap-3 w-100 justify-content-end px-5 py-3 border-top border-white">
                <button class="btn bg-primary text-white edit-btn" style="width: 8%;">Edit</button>
                <button class="btn bg-success text-white save-btn d-none" style="width: 8%;">Save</button>
                <button class="btn bg-primary text-white" style="width: 8%;" onclick="openAssignModal('${app.id}')">Assign</button>
                <button class="btn bg-error text-white" style="width: 8%;" onclick="handleDelete('${app.id}')">Delete</button>
            </div>
        </div>
    </div>
    `;
}

async function loadApps() {
    try {
        const container = document.getElementById('app-container');
        const apps = await getAllApplications();
        container.innerHTML = apps.map(renderAppCard).join('');
    } catch (err) {
        console.error("Failed to load apps:", err);
        document.getElementById('app-container').innerHTML = '<p class="text-danger">Failed to load apps.</p>';
    }
}

loadApps();

window.handleDelete = async function(appId) {
    const confirmDelete = confirm("Are you sure you want to delete this Appplication?");
    if (!confirmDelete) return;

    try {
        await deleteApplication(appId);
        alert("Application deleted successfully.");
        loadApps(); // Reload list after deletion
    } catch (error) {
        console.error("Error deleting application:", error);
        alert("Failed to delete Application.");
    }
};


document.addEventListener('click', async function (e) {
    const card = e.target.closest('.app-card');
    if (!card) return;

    const appId = card.getAttribute('data-app-id');

    if (e.target.classList.contains('edit-btn')) {
        card.querySelectorAll('input').forEach(input => input.removeAttribute('readonly'));
        card.querySelector('.edit-btn').classList.add('d-none');
        card.querySelector('.save-btn').classList.remove('d-none');
    }

    if (e.target.classList.contains('save-btn')) {
        const programme = card.querySelector('.editable-programme').value;
        const status = card.querySelector('.editable-status').value;
        

                // Validate the role value
        if (!['submitted', 'approved', 'rejected', 'draft'].includes(status.toLowerCase())) {
            alert("Invalid status! status must be 'submitted', 'approved', 'rejected', or 'draft'");
            return;  // Prevent saving if the role is invalid
        }

        try {
            await editApplication(appId, { programme, status });
            alert("Application updated successfully.");

            // Lock the inputs again
            card.querySelectorAll('input').forEach(input => input.setAttribute('readonly', true));
            card.querySelector('.edit-btn').classList.remove('d-none');
            card.querySelector('.save-btn').classList.add('d-none');
        } catch (error) {
            console.error(error);
            alert("Failed to update application.");
        }
    }
});

let selectedAppId = null;

window.openAssignModal = async function(appId) {
    selectedAppId = appId;
    const modal = document.getElementById('assignModal');
    const list = document.getElementById('reviewerList');
    list.innerHTML = '';

    try {
        const reviewers = await getAllReviewers(); // get all reviewers
        reviewers.forEach(r => {
            const div = document.createElement('div');
            div.className = 'form-check';

            const input = document.createElement('input');
            input.type = 'checkbox';
            input.className = 'form-check-input';
            input.name = 'reviewer';
            input.value = r.id;
            input.id = `reviewer-${r.id}`;

            const label = document.createElement('label');
            label.className = 'form-check-label';
            label.htmlFor = input.id;
            label.textContent = r.name;

            div.appendChild(input);
            div.appendChild(label);
            list.appendChild(div);
        });

        modal.style.display = 'block';
    } catch (err) {
        console.error('Failed to load reviewers:', err);
        list.innerHTML = '<p class="text-danger">Error loading reviewers</p>';
    }
};

window.closeAssignModal = function () {
    const modal = document.getElementById('assignModal');
    modal.style.display = 'none';
};


window.submitReviewerAssignments = async function() {
    const checkedBoxes = document.querySelectorAll('input[name="reviewer"]:checked');
    const reviewerIds = Array.from(checkedBoxes).map(box => box.value);

    if (reviewerIds.length === 0) {
        alert('Please select at least one reviewer.');
        return;
    }

    try {
        await assignReviewersToApp(selectedAppId, reviewerIds); // controller function
        closeAssignModal();
        alert('Reviewers assigned successfully!');
        loadApps(); // refresh the list
    } catch (err) {
        console.error('Failed to assign reviewers:', err);
        alert('Failed to assign reviewers.');
    }
};



function formatDate(timestamp) {
  if (!timestamp || !timestamp.toDate) return 'N/A';

  const date = timestamp.toDate(); // convert Firestore Timestamp to JS Date
  const day = String(date.getDate()).padStart(2, '0');
  const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-based
  const year = date.getFullYear();

  return `${day}/${month}/${year}`;
}

window.filterApplications = function(status) {
    const cards = document.querySelectorAll('.app-card');
    cards.forEach(function(card) {
        const appStatus = card.getAttribute('app-status');
        if (status === 'all' || appStatus === status) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
};


</script>

