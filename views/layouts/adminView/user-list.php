<div class="mb-3 d-flex">
  <div class="d-flex align-items-center gap-2">
    <label for="statusFilter" class="form-label m-0 fs-5 fw-bold">Show:</label>
    <select id="statusFilter" class="form-select bg-white" onchange="filterUser(this.value)">
        <option value="all">All</option>
        <option value="reviewer">Reviewer</option>
        <option value="student">Student</option>
        <option value="admin">Admin</option>
    </select>
  </div>
</div>

<!-- Container where cards will be injected -->
<div id="user-container"></div>

<script type="module">
import { getAllUsers, deleteUser, editUser } from '../../controllers/admin-controller.js';

function renderUserCard(user) {
    const collapseId = "user" + user.id;
    return `
    <div class="card mb-2 shadow-sm rounded user-card" user-role="${user.role}" data-user-id="${user.id}">
        <div class="content-item bg-white p-4 d-flex align-items-center justify-content-between w-100">
            <div class="d-flex align-items-center gap-3">
                <i class="fa-regular fa-circle-user fa-2x"></i>
                <span>${user.name}</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="btn toggle-btn" data-bs-toggle="collapse" data-bs-target="#${collapseId}">
                    <i class="fa fa-caret-down"></i> 
                </button>
            </div>
        </div>

        <div class="collapse bg-secondary" id="${collapseId}">
            <div class="card-body border-top d-flex justify-content-end px-5">
                <div class="content-info d-flex flex-column gap-2">
                    <strong class="mb-2">Label</strong>
                    <div class="fw-bold">id</div>
                    <div class="fw-bold">name</div>
                    <div class="fw-bold">email</div>
                    <div class="fw-bold">password</div>
                    <div class="fw-bold">role</div>
                </div>
                <div class="content-info d-flex flex-column gap-2">
                    <strong>User Info</strong>
                    <div>${user.id}</div>
                    <input type="text" class="form-control form-control-sm editable-name bg-white" value="${user.name}" readonly>
                    <input type="text" class="form-control form-control-sm editable-email bg-white" value="${user.email}" readonly>
                    <input type="text" class="form-control form-control-sm editable-password bg-white" value="${user.password}" readonly>
                    <input type="text" class="form-control form-control-sm editable-role bg-white" value="${user.role}" readonly>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3 w-100 justify-content-end px-5 py-3 border-top border-white">
                <button class="btn bg-primary text-white edit-btn" style="width: 8%;">Edit</button>
                <button class="btn bg-success text-white save-btn d-none" style="width: 8%;">Save</button>
                <button class="btn bg-error text-white" style="width: 8%;" onclick="handleDelete('${user.id}')">Delete</button>
            </div>
        </div>
    </div>
    `;
}

async function loadUsers() {
    try {
        const container = document.getElementById('user-container');
        const users = await getAllUsers();
        container.innerHTML = users.map(renderUserCard).join('');
    } catch (err) {
        console.error("Failed to load users:", err);
        document.getElementById('user-container').innerHTML = '<p class="text-danger">Failed to load users.</p>';
    }
}

loadUsers();

window.handleDelete = async function(userId) {
    const confirmDelete = confirm("Are you sure you want to delete this user?");
    if (!confirmDelete) return;

    try {
        await deleteUser(userId);
        alert("User deleted successfully.");
        loadUsers(); // Reload list after deletion
    } catch (error) {
        console.error("Error deleting user:", error);
        alert("Failed to delete user.");
    }
};

document.addEventListener('click', async function (e) {
    const card = e.target.closest('.user-card');
    if (!card) return;

    const userId = card.getAttribute('data-user-id');

    if (e.target.classList.contains('edit-btn')) {
        card.querySelectorAll('input').forEach(input => input.removeAttribute('readonly'));
        card.querySelector('.edit-btn').classList.add('d-none');
        card.querySelector('.save-btn').classList.remove('d-none');
    }

    if (e.target.classList.contains('save-btn')) {
        const name = card.querySelector('.editable-name').value;
        const email = card.querySelector('.editable-email').value;
        const password = card.querySelector('.editable-password').value;
        const role = card.querySelector('.editable-role').value;

                // Validate the role value
        if (!['student', 'reviewer', 'admin'].includes(role.toLowerCase())) {
            alert("Invalid role! Role must be 'student', 'reviewer', or 'admin'.");
            return;  // Prevent saving if the role is invalid
        }

        try {
            await editUser(userId, { name, email, password, role });
            alert("User updated successfully.");

            // Lock the inputs again
            card.querySelectorAll('input').forEach(input => input.setAttribute('readonly', true));
            card.querySelector('.edit-btn').classList.remove('d-none');
            card.querySelector('.save-btn').classList.add('d-none');
        } catch (error) {
            console.error(error);
            alert("Failed to update user.");
        }
    }
});


window.filterUser = function(role) {
    const cards = document.querySelectorAll('.user-card');
    cards.forEach(function(card) {
        const cardRole = card.getAttribute('user-role');
        if (role === 'all' || cardRole === role) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
};
</script>
