<script>
import {VueDadata} from 'vue-dadata';
import 'vue-dadata/dist/style.css';
import {mask} from 'vue-the-mask'

export default {
  components: {
    VueDadata
  },
  directives: {
    mask
  },
  data() {
    return {
      form: {
        comment: '',
        fullName: '',
        address: '',
        email: '',
        mobile: '',
        file: '',
      },
      errorMessage: '',
      successMessage: '',
      token: "72bb618072b8f957f65b06a2d238e79ae9cd95f0",
      suggestFullNameUrl: "http://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/fio",
      suggestAddressUrl: 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address'
    };
  },
  methods: {
    async submitForm() {
      this.errorMessage = '';
      this.successMessage = '';

      const formData = new FormData();
      if (!this.form.fullName || !this.form.mobile) {
        this.errorMessage = "Заполните все необходимые поля";
        return;
      }
      if (this.form.email.includes('@gmail.com')) {
        this.errorMessage = "Регистрация пользователей с таким почтовым адресом невозможна.";
        return;
      }
      for (const key in this.form) {
        formData.append(key, this.form[key]);
      }

      try {
        const response = await fetch('http://localhost:8000/form', {
          method: 'POST',
          body: formData,
        });
        const result = await response.json();
        if (!response.ok) {
          this.errorMessage = result.message;
        } else {
          this.successMessage = 'Форма успешно отправлена!';
          this.resetForm();
        }
      } catch (error) {
        this.errorMessage = 'Ошибка при отправке формы.';
      }
    },
    resetForm() {
      this.form = {
        comment: '',
        fullName: '',
        address: '',
        email: '',
        mobile: '',
      };
      this.$refs.fileInput.value = '';
    },
    handleFileUpload(event) {
      this.form.file = event.target.files[0];
    },
  },
};
</script>

<template>
  <h1>Форма обратной связи</h1>
  <div class="form-wrapper">
    <form @submit.prevent="submitForm" class="form">
      <div>
        <label for="fullName">ФИО (обязательно):</label>
        <vue-dadata
            v-model="form.fullName"
            :token="token"
            :url="suggestFullNameUrl"
            :required="true"/>
      </div>
      <div>
        <label for="comment">Комментарий:</label>
        <textarea v-model="form.comment" id="comment" rows="7"></textarea>
      </div>
      <div>
        <label for="address">Адрес:</label>
        <vue-dadata
            v-model="form.address"
            :token="token"
            :url="suggestAddressUrl"/>
      </div>
      <div>
        <label for="email">Email:</label>
        <input type="email" v-model="form.email" id="email"/>
      </div>
      <div>
        <label for="mobile">Мобильный телефон (обязательно):</label>
        <input type="tel" v-model="form.mobile" v-mask="'+# (###) ### ## ##'"
               id="mobile"/>
      </div>
      <div>
        <label class="custom-file-upload" for="file">Файл:</label>
        <input type="file" ref="fileInput" @change="handleFileUpload" id="file"/>
        <div>{{ form.file?.name }}</div>
      </div>
      <button type="submit">Отправить</button>
    </form>
  </div>
  <div v-if="errorMessage" class="error">{{ errorMessage }}</div>
  <div v-if="successMessage" class="success">{{ successMessage }}</div>
</template>

<style scoped>

.form-wrapper {
  display: flex;
  justify-content: center;
}

.form {
  width: 25%;
}

@media (max-width: 992px) {
  .form {
    width: 30%;
  }
}

@media (max-width: 768px) {
  .form {
    width: 40%;
  }
}

@media (max-width: 576px) {
  .form {
    width: 55%;
  }
}

input:not([type=file]),
textarea {
  display: block;
  font-size: 14px;
  width: 100%;
  outline: none;
  border-radius: 4px;
  border: 1px solid #f1c40f;
  transition: .3s;
  box-sizing: border-box;
  padding: 0 5px;
  margin: 10px 0;
}

input:not([type=file]) {
  height: 30px !important;
}

input[type="file"] {
  display: none;
}

label {
  display: block;
  margin: 5px 0;
}

button,
.custom-file-upload {
  border-radius: 4px;
  border: 1px solid #f1c40f;
  display: inline-block;
  padding: 6px 12px;
  cursor: pointer;
  margin: 10px 0;
  width: 50%;
}

button:hover,
.custom-file-upload:hover {
  background-color: #e0e0e0;
}

.error {
  color: red;
}

.success {
  color: green;
}
</style>
