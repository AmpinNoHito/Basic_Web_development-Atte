<template>
  <div class="rl">
    <div class="rl__title">
      会員登録
    </div>
    <form class="rl__form">
      <div class="rl__form-item">
        <input
            class="rl__input"
            type="text"
            v-model="name"
            placeholder="名前">
        <p class="rl__error" v-show="errors.name">
          {{errors.name[0]}}
        </p>
      </div>
      <div class="rl__form-item">
        <input
            class="rl__input"
            type="email"
            v-model="email"
            placeholder="メールアドレス">
        <p class="rl__error" v-show="errors.email">
          {{errors.email[0]}}
        </p>
      </div>
      <div class="rl__form-item">
        <input
          class="rl__input"
          v-model="password"
          type="password"
          name="パスワード"
          placeholder="パスワード">
        <p class="rl__error" v-show="errors.password">
          {{errors.password[0]}}
        </p>
      </div>
      <div class="rl__form-item">
        <input
          class="rl__input"
          v-model="confPassword"
          type="password"
          name="確認用パスワード"
          placeholder="確認用パスワード">
      </div>
      <button class="rl__btn" type="button" @click="register">会員登録</button>
    </form>
    <div class="rl__to-login">
      <p class="rl__text">アカウントをお持ちの方はこちらから</p>
      <nuxt-link class="rl__link" to="/login">ログイン</nuxt-link>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'withoutNav',
  data() {
    return {
      name: '',
      email: '',
      password: '',
      confPassword: '',
      errors: {
        name: '',
        email: '',
        password: '',
      }
    }
  },
  methods: {
    async register() {
      if (this.password !== this.confPassword) {
        alert('パスワードと確認用パスワードが一致していません。');
        return;
      }
      const sendData = {
        'name': this.name,
        'email': this.email,
        'password': this.password,
      }
      await this.$axios.get('/sanctum/csrf-cookie');
      try {
        const res = await this.$axios.post('/api/auth/register', sendData);
        alert(res.data.message);
        this.$router.push('/login');
      } catch (error) {
        const errors = error.response.data.errors;
        const errorMessage = error.response.data.message;
        if (errors) {
          this.errors['name'] = (errors.name) ? errors.name : '';
          this.errors['email'] = (errors.email) ? errors.email : '';
          this.errors['password'] = (errors.password) ? errors.password : '';
        }
        alert(errorMessage);
      }
    },
  },
};
</script>

<style lang="scss">
  .rl {
    height: calc(100vh - 65px);
    padding-top: 50px;
    display: flex;
    flex-direction: column;
    align-items: center;

    &__title {
      font-weight: bold;
      margin-bottom: 20px;
    }

    &__form {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    &__form-item {
      margin-bottom: 10px;
    }

    &__error {
      margin-top: 2px;
      font-size: 0.5em;
      font-weight: bold;
      color: red;
      text-align: right;
    }

    &__input {
      width: 250px;
      padding: 5px 8px;
      background-color: #eee;
      border: 1px solid #666;
      border-radius: 3px;
      &::placeholder {
        font-size: 0.8em;
      }
    }

    &__btn {
      cursor: pointer;
      margin-bottom: 20px;
      width: 100%;
      padding: 5px;
      background-color: #33e;
      color: #fff;
      border: none;
      border-radius: 3px;
      font-size: 0.8em;
    }
    
    &__to-login {
      text-align: center;
      font-size: 0.7em;
    }
    
    &__text {
      color: #666;
      margin-bottom: 3px;
    }

    &__link {
      color: #33e;
    }
  }
</style>