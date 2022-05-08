<template>
<div>
  <header class="header">
    <div class="header__inner">
      <p class="header__logo">
        <nuxt-link to="/">
          Atte
        </nuxt-link>
      </p>
      <div class="header__nav-list">
        <nuxt-link 
          class="header__nav-item"
          to="/">
          ホーム
        </nuxt-link>
        <span 
          class="header__nav-item"
          @click="toAttendance">
          日付一覧
        </span>
        <span 
          class="header__nav-item"
          v-if="$store.state.auth.authUser"
          @click="logout">
          ログアウト
        </span>
        <nuxt-link
          to="/login" 
          class="header__nav-item"
          v-if="!$store.state.auth.authUser">
          ログイン
        </nuxt-link>
        <nuxt-link
          to="/register" 
          class="header__nav-item"
          v-if="!$store.state.auth.authUser">
          会員登録
        </nuxt-link>
      </div>
    </div>
  </header>
  <nuxt/>
  <footer class="footer">
    <p class="footer_text">Atte, inc.</p>
  </footer>
</div>
</template>


<script>
export default {
  methods: {
    toAttendance() {
      const now = new Date();
      this.$router.push(`/attendance/${this.formatDate(now, 'Y-n-j')}`);
    },
    async logout() { 
      try {
        const res = await this.$axios.post('api/auth/logout');
        alert(res.data.message);
        this.$store.commit('auth/setAuthUser', null);
        this.$store.commit('register/resetState');
      } catch (error) {
        console.log(error);
      }
    }
  },
};
</script>

<style lang="scss">
.header {
  background-color: #fff;
  width: 100%;
  
  &__inner {
    max-width: 940px;
    margin: 0 auto;
    padding: 0 30px;
    height: 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  &__logo {
    font-size: 1.2em;
  }

  &__nav-list {
    display: flex;    
    align-items: center;
    height: 100%;
    padding-right: 5px;
    font-size: 0.8em;
  }

  &__nav-item {
    cursor: pointer;
    &:not(:last-child) {
      margin-right: 30px;
    }
  }
}

.footer {
  height: 25px;
  background-color: #fff;
  font-size: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
}

</style>