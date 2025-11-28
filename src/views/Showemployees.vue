<template>
  <div class="container my-5">
    <h2 class="text-center mb-4">รายชื่อพนักงาน</h2>
    
    <div v-if="loading" class="text-center">
      <p>กำลังโหลดข้อมูล...</p>
    </div>

    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <div class="row">
      <div class="col-md-4" v-for="employee in employees" :key="employee.emp_id">
        <div class="card shadow-sm mb-4">
          <img 
            v-if="employee.image"
            :src="'http://localhost:8081/final67704228/api.php/uploads/' + employee.image" 
            class="card-img-top" 
            style="height: 300px; object-fit: cover;"
            :alt="employee.first_name"
            @error="handleImageError"
          >
          <img 
            v-else
            src="http://localhost:8081/final67704228/api.php/uploads/" 
            class="card-img-top" 
            style="height: 300px; object-fit: cover;"
            alt="No image"
          >
          <div class="card-body text-center">
            <h5 class="card-title">{{ employee.first_name }} {{ employee.last_name }}</h5>
            <p class=" text-primary">
              <strong>เบอร์โทร:</strong> {{ employee.phone }}
            </p>
           
          </div>
        </div>
      </div>
    </div>

    <div v-if="!loading && employees.length === 0" class="text-center">
      <p class="text-muted">ไม่มีข้อมูลพนักงาน</p>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";

export default {
  name: "ShowEmployees",
  setup() {
    const employees = ref([]);
    const loading = ref(true);
    const error = ref(null);

    // ฟังก์ชันดึงข้อมูลพนักงานจาก API
    const fetchEmployees = async () => {
      try {
        const response = await fetch("http://localhost:8081/final67704228/api.php/employees.php", {
          method: "GET",
          headers: {
            "Content-Type": "application/json"
          }
        });

        if (!response.ok) {
          throw new Error("ไม่สามารถดึงข้อมูลได้");
        }

        const result = await response.json();
        if (result.success) {
          employees.value = result.data;
        } else {
          error.value = result.message || "ไม่พบข้อมูล";
        }

      } catch (err) {
        error.value = err.message;
      } finally {
        loading.value = false;
      }
    };

    const handleImageError = (event) => {
      console.error('ไม่สามารถโหลดรูปภาพได้:', event.target.src);
      event.target.src = 'https://via.placeholder.com/300x300?text=No+Image';
    };

    onMounted(() => {
      fetchEmployees();
    });

    return {
      employees,
      loading,
      error,
      handleImageError
    };
  }
};
</script>

<style scoped>
.card {
  transition: transform 0.2s;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.card-img-top {
  border-bottom: 1px solid #dee2e6;
}
</style>