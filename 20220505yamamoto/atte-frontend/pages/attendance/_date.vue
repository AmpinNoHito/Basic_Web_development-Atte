<template>
  <div class="at">
    <div class="at__date-selector">
      <button 
        class="at__select-btn at__select-btn--pre"
        type="button"
        @click="toNextDate">
        &lt;
      </button>
      <p class="at__date"> {{date}} </p>
      <button
        class="at__select-btn at__select-btn--next"
        type="button"
        @click="toLastDate">
        >
      </button>
    </div>
    <div class="at__no-works" v-if="!works.length">
      <p class="at__message">勤務履歴はありません。</p>
    </div>
    <div class="at__works" v-else>
      <table class="at__table">
        <tr>
          <th>名前</th>
          <th>勤務開始</th>
          <th>勤務終了</th>
          <th>休憩時間</th>
          <th>勤務時間</th>
        </tr>
        <tr v-for="(work, index) in works" :key="work.id">
          <td>{{work.user.name}}</td>
          <td>{{work.start | withoutMS}}</td>
          <td>{{work.end | withoutMS}}</td>
          <td>{{restSum(index)}}</td>
          <td>{{workSum(index)}}</td>
        </tr>
      </table>
      <Pagination
        class="at__pagination"
        :range="range"
        :current-page="currentPage"
        :last-page="lastPage"
        :front-dot="frontDot"
        :end-dot="endDot"
        :size-check="sizeCheck"
        @clicked="changePage($event);
                  dotState();
                  getWorks()"
        />
      </div>
  </div>
</template>

<script>
import auth from '~/middleware/auth';
export default {
  
  middleware: [auth],
  async asyncData({ route , $axios}) {
    const res = await $axios.get(`api/work/index/${route.params.date}`);
    const range = 7;
    const data = res.data.data;
    const sizeCheck = (data.last_page >= range + 4) ? true : false;
    return {
      works: data.data,
      range: range,
      currentPage: data.current_page,
      lastPage: data.last_page,
      sizeCheck: sizeCheck,
    }
  },
  data() {
    return {
      date: this.$route.params.date,
      frontDot: false,
      endDot: false,
    }
  },
  filters: {
    withoutMS(value) {
      return value.slice(0, value.length - 3);
    },
  },
  methods: {
    async getWorks() {
      const res = await this.$axios.get(`/api/work/index/${this.date}?page=${this.currentPage}`);
      this.works = res.data.data.data;
    },
    toNextDate() {
      const dt = new Date(this.date);
      dt.setDate(dt.getDate() + 1);
      const param = this.formatDate(dt, 'Y-n-j');
      this.$router.push(`/attendance/${param}`);
    },
    toLastDate() {
      const dt = new Date(this.date);
      dt.setDate(dt.getDate() - 1);
      const param = this.formatDate(dt, 'Y-n-j');
      this.$router.push(`/attendance/${param}`);
    },
    restSum(index) {
      const rests = this.works[index].rests;
      if (!rests.length) {
        return '';
      }

      const sumInMS = this.getRestSumInMS(rests);

      const hours = Math.floor(sumInMS / (60 * 60 * 1000));
      const minutes =  Math.floor(sumInMS / (60 * 1000)) % 60;
      const seconds =  Math.floor(sumInMS / 1000) % 60;
      if (!hours && !minutes && !seconds) {
        return '';
      }
      
      return `
        ${(hours < 10) ? `0${hours}` : hours}:${(minutes < 10) ? `0${minutes}` : minutes}:${(seconds < 10) ? `0${seconds}` : seconds}
        `;
    },
    workSum(index) {
      const startInMS = new Date(`${this.date} ${this.works[index].start}`).getTime();
      const endInMS = new Date(`${this.date} ${this.works[index].end}`).getTime();

      let restSumInMS;
      if (this.restSum(index)) {
        restSumInMS = this.getRestSumInMS(this.works[index].rests);
      }

      const sumInMS = (restSumInMS) ? 
        endInMS - startInMS - restSumInMS : 
        endInMS - startInMS;

      const hours = Math.floor(sumInMS / (60 * 60 * 1000));
      const minutes =  Math.floor(sumInMS / (60 * 1000)) % 60;
      const seconds =  Math.floor(sumInMS / 1000) % 60;
      return `
        ${(hours < 10) ? `0${hours}` : hours}:${(minutes < 10) ? `0${minutes}` : minutes}:${(seconds < 10) ? `0${seconds}` : seconds}
        `;
    },
    getRestSumInMS(rests) {
      const diffs = [];
      rests.forEach(rest => {
        const startInMS = new Date(`${this.date} ${rest.start}`).getTime();
        const endInMS = new Date(`${this.date} ${rest.end}`).getTime();
        const diff = endInMS - startInMS;
        diffs.push(diff);
      });
      return diffs.reduce((pre, next) => pre += next, 0);
    }
  },
  mounted() {
    this.dotState();
  },
};

</script>


<style lang="scss">
  .at {
    height: calc(100vh - 65px);
    display: flex;
    flex-direction: column;
    align-items: center;

    &__date-selector {
      display: flex;
      align-items: center;
      padding: 20px;
    }

    &__select-btn {
      cursor: pointer;
      border: 1px solid steelblue;
      border-radius: 2px;
      background-color: #fff;
      color: steelblue;
      padding: 0 8px;
      
      &--pre {
        margin-right: 20px;
      }

      &--next {
        margin-left: 20px;
      }
    }

    &__message {
      margin-top: 100px;
    }

    &__works {
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    &__table {
      width: 80%;

      & th {
        text-align-last: left;
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
        padding: 15px 0;
        width: 20%;
        &:first-of-type {
          padding-left: 20px;
        }
      }

      & td {
        border-bottom: 1px solid #000;
        padding: 10px 0;
        width: 20%;
        &:first-of-type {
          padding-left: 20px;
        }
      }
    }

    &__pagination {
      position: fixed;
      bottom: 25px;
    }
  }
</style>