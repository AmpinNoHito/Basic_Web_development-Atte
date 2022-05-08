<template>
  <div class="record">
    <div class="record__greeting">
      <p v-show="$store.state.auth.authUser">
        {{showUsername}}さんお疲れ様です！
      </p>
    </div>
    <div class="record__btn-wrapper">
      <button
        type="button"
        class="record__btn"
        :disabled="!$store.state.register.canStartWork"
        @click="startWork">
        勤務開始
      </button>
      <button
        type="button"
        class="record__btn"
        :disabled="!$store.state.register.canEndWork"
        @click="endWork">
        勤務終了
      </button>
    </div>
    <div class="record__btn-wrapper">
      <button
        type="button"
        class="record__btn"
        :disabled="!$store.state.register.canStartRest"
        @click="startRest">
        休憩開始
      </button>
      <button
        type="button"
        class="record__btn"
        :disabled="!$store.state.register.canEndRest"
        @click="endRest">休憩終了
      </button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {

    }
  },
  computed: {
    showUsername() {
      const currentUser = this.$store.state.auth.authUser;
      const userName = (currentUser) ? this.$store.state.auth.authUser.name : '';
      return userName;
    }
  },
  methods: {
    async startWork() {
      const currentUser = this.$store.state.auth.authUser;
      if (!currentUser) {
        alert('この操作はログインしなければ実行できません。')
        return;
      }

      const now = new Date();
      
      const sendData = {
        userId: currentUser.id,
        date: this.formatDate(now, 'Y-n-j'),
        start: this.formatTime(now, 'H:i:s'), 
      }
      
      const res = await this.$axios.post('/api/work/start', sendData);
      alert(res.data.message);
      this.$store.commit('register/toggleStartWork');
      this.$store.commit('register/toggleEndWork');
    },
    async endWork() {
      const currentUser = this.$store.state.auth.authUser;
      if (!currentUser) {
        alert('この操作はログインしなければ実行できません。')
        return;
      }

      const now = new Date();
      
      const sendData = {
        userId: currentUser.id,
        date: this.formatDate(now, 'Y-n-j'),
        end: this.formatTime(now, 'H:i:s'), 
      }
      
      const res = await this.$axios.post('/api/work/end', sendData);
      alert(res.data.message);
      this.$store.commit('register/toggleEndWork');
      this.$store.commit('register/toggleStartRest');
    },
    async startRest() {
      const currentUser = this.$store.state.auth.authUser;
      if (!currentUser) {
        alert('この操作はログインしなければ実行できません。')
        return;
      }

      const now = new Date();
      
      const sendData = {
        userId: currentUser.id,
        date: this.formatDate(now, 'Y-n-j'),
        lastDate: this.formatDate(now, 'Y-n-j', true),
        start: this.formatTime(now, 'H:i:s'), 
      }
      const res = await this.$axios.post('/api/rest/start', sendData);
      alert(res.data.message);
      this.$store.commit('register/toggleStartRest');
      this.$store.commit('register/toggleEndRest');
    },
    async endRest() {
      const currentUser = this.$store.state.auth.authUser;
      if (!currentUser) {
        alert('この操作はログインしなければ実行できません。')
        return;
      }

      const now = new Date();
      
      const sendData = {
        userId: currentUser.id,
        date: this.formatDate(now, 'Y-n-j'),
        end: this.formatTime(now, 'H:i:s'), 
      }
      
      const res = await this.$axios.post('/api/rest/end', sendData);
      alert(res.data.message);
      this.$store.commit('register/toggleStartRest');
      this.$store.commit('register/toggleEndRest');
    },
  },
}
</script>

<style lang="scss">
  .record {
    height: calc(100vh - 65px);
    display: flex;
    flex-direction: column;
    align-items: center;

    &__greeting {
      padding: 25px 0;
      font-weight: bold;
    }

    &__btn-wrapper {
      display: flex;
      width: 60%;
      margin-bottom: 20px;
      & :first-child {
        margin-right: 20px;
      }
    }

    &__btn {
      cursor: pointer;
      width: 50%;
      height: 100px;
      background-color: #fff;
      border: none;
      font-weight: bold;
    }
  }
</style>
