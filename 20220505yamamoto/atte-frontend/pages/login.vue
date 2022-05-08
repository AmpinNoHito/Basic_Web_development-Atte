<template>
  <div class="rl">
    <div class="rl__title">
      ログイン
    </div>
    <form class="rl__form">
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
      <button class="rl__btn" type="button" @click="login">ログイン</button>
    </form>
    <div class="rl__to-login">
      <p class="rl__text">アカウントをお持ちでない方はこちらから</p>
      <nuxt-link class="rl__link" to="/register">会員登録</nuxt-link>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'withoutNav',
  data() {
    return {
      email: '',
      password: '',
      errors: {
        email: '',
        password: '',
      }
    }
  },
  methods: {
    async login() {
      const sendData = {
        'email': this.email,
        'password': this.password,
      }
      await this.$axios.get('/sanctum/csrf-cookie');
      try {
        const res = await this.$axios.post('api/auth/login', sendData);
        alert(res.data.message);
        await this.$store.commit('auth/setAuthUser', res.data.userInfo);
        if (res.status === 200) {
          this.$router.push('/');
        }
      } catch (error) {
        const errors = error.response.data.errors;
        this.errors['email'] = (errors.email) ? errors.email : '';
        this.errors['password'] = (errors.password) ? errors.password : '';
      }
    },
  },
};
</script>