<template>
  <div class="container mt-4">
    <h2 class="mb-3">EmployeeList</h2>

    <div class="mb-3">
      <button class="btn btn-primary" @click="openAddModal">Add+</button>
    </div>

    <table class="table table-bordered table-striped">
      <thead class="table-primary">
        <tr>
          <th>รูปภาพ</th>
          <th>รหัส</th>
          <th>ชื่อ</th>
          <th>นามสกุล</th>
          <th>ที่อยู่</th>
          <th>เบอร์โทร</th>
          <th>แก้ไข / ลบ</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="employee in employees" :key="employee.emp_id">
          <td>
            <img
              v-if="employee.image"
              :src="'http://localhost:8081/final67704228/api.php/uploads/' + employee.image"
              width="100"
            />
          </td>
          <td>{{ employee.emp_id }}</td>
          <td>{{ employee.first_name }}</td>
          <td>{{ employee.last_name }}</td>
          <td>{{ employee.address }}</td>
          <td>{{ employee.phone }}</td>
          <td>
            <button class="btn btn-warning btn-sm me-2" @click="openEditModal(employee)">
              แก้ไข
            </button>
            <button class="btn btn-danger btn-sm" @click="deleteEmployee(employee.emp_id)">
              ลบ
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="loading" class="text-center"><p>กำลังโหลดข้อมูล...</p></div>
    <div v-if="error" class="alert alert-danger">{{ error }}</div>

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ isEditMode ? "แก้ไขพนักงาน" : "เพิ่มพนักงานใหม่" }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveEmployee">
              <div class="mb-3">
                <label class="form-label">รูปภาพ</label>
                <input
                  type="file"
                  @change="handleFileUpload"
                  class="form-control"
                  :required="!isEditMode"
                />
                <div v-if="isEditMode && editForm.image">
                  <p class="mt-2">รูปเดิม:</p>
                  <img
                    :src="'http://localhost:8081/final67704228/api.php/uploads/' + editForm.image"
                    width="100"
                  />
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">รหัสพนักงาน</label>
                <input v-model="editForm.emp_id" type="text" class="form-control" :disabled="isEditMode" required />
              </div>
              <div class="mb-3">
                <label class="form-label">ชื่อ</label>
                <input v-model="editForm.first_name" type="text" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">นามสกุล</label>
                <input v-model="editForm.last_name" type="text" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">ที่อยู่</label>
                <input v-model="editForm.address" type="text" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">เบอร์โทร</label>
                <input v-model="editForm.phone" type="text" class="form-control" required />
              </div>

              <button type="submit" class="btn btn-success">
                {{ isEditMode ? "บันทึกการแก้ไข" : "บันทึกพนักงานใหม่" }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";

export default {
  name: "EmployeeList",
  setup() {
    const employees = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const isEditMode = ref(false);
    const editForm = ref({
      emp_id: "",
      first_name: "",
      last_name: "",
      address: "",
      phone: "",
      image: ""
    });
    const newImageFile = ref(null);
    let modalInstance = null;

   const fetchEmployees = async () => {
      try {
        const res = await fetch("http://localhost:8081/final67704228/api.php/employees.php");
        const data = await res.json();
        if (data.success) {
          // เรียงลำดับตามรหัสพนักงาน จากน้อยไปมาก
          employees.value = data.data.sort((a, b) => {
            return a.emp_id.localeCompare(b.emp_id);
          });
        } else {
          employees.value = [];
        }
      } catch (err) {
        error.value = err.message;
      } finally {
        loading.value = false;
      }
    };

    const openAddModal = () => {
      isEditMode.value = false;
      editForm.value = {
        emp_id: "",
        first_name: "",
        last_name: "",
        address: "",
        phone: "",
        image: ""
      };
      newImageFile.value = null;
      
      const modalEl = document.getElementById("editModal");
      modalInstance = new window.bootstrap.Modal(modalEl);
      modalInstance.show();

      const fileInput = modalEl.querySelector('input[type="file"]');
      if (fileInput) fileInput.value = "";
    };

    const openEditModal = (employee) => {
      isEditMode.value = true;
      editForm.value = { ...employee };
      newImageFile.value = null;
      const modalEl = document.getElementById("editModal");
      modalInstance = new window.bootstrap.Modal(modalEl);
      modalInstance.show();
    };

    const handleFileUpload = (event) => {
      newImageFile.value = event.target.files[0];
    };

    const saveEmployee = async () => {
      const formData = new FormData();
      formData.append("action", isEditMode.value ? "update" : "add");
      formData.append("emp_id", editForm.value.emp_id);
      formData.append("first_name", editForm.value.first_name);
      formData.append("last_name", editForm.value.last_name);
      formData.append("address", editForm.value.address);
      formData.append("phone", editForm.value.phone);
      if (newImageFile.value) formData.append("image", newImageFile.value);

      try {
        const res = await fetch("http://localhost:8081/final67704228/api.php/employees.php", {
          method: "POST",
          body: formData
        });
        const result = await res.json();
        if (result.message) {
          alert(result.message);
          fetchEmployees();
          modalInstance.hide();
        } else if (result.error) {
          alert(result.error);
        }
      } catch (err) {
        alert(err.message);
      }
    };

    const deleteEmployee = async (emp_id) => {
      if (!confirm("คุณแน่ใจหรือไม่ที่จะลบพนักงานนี้?")) return;

      const formData = new FormData();
      formData.append("action", "delete");
      formData.append("emp_id", emp_id);

      try {
        const res = await fetch("http://localhost:8081/final67704228/api.php/employees.php", {
          method: "POST",
          body: formData
        });
        const result = await res.json();
        if (result.message) {
          alert(result.message);
          employees.value = employees.value.filter((e) => e.emp_id !== emp_id);
        } else if (result.error) {
          alert(result.error);
        }
      } catch (err) {
        alert(err.message);
      }
    };

    onMounted(fetchEmployees);

    return {
      employees,
      loading,
      error,
      editForm,
      isEditMode,
      openAddModal,
      openEditModal,
      handleFileUpload,
      saveEmployee,
      deleteEmployee
    };
  }
};
</script>