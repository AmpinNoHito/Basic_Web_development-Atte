import Vue from 'vue';

Vue.mixin({
  methods: {
    formatDate(date, format, lastDate = false) {
      format = format.replace(/Y/, date.getFullYear());
      format = format.replace(/n/, date.getMonth() + 1);
      format = (lastDate) ? 
        format.replace(/j/, date.getDate() - 1):
        format.replace(/j/, date.getDate());
      return format;
    },
    formatTime(time, format) {
      const hours = time.getHours();
      const minutes = time.getMinutes();
      const seconds = time.getSeconds();
      format = format.replace(
          /H/, (hours < 10) ? `0${hours}` : hours
        );
      format = format.replace(
          /i/, (minutes < 10) ? `0${minutes}` : minutes
        );
      format = format.replace(
          /s/, (seconds < 10) ? `0${seconds}` : seconds
        );
      return format;
    },
    dotState() {
      if (!this.sizeCheck) {
        this.frontDot = false;
        this.endDot = false;
      } else if (this.currentPage <= this.range) {
        this.frontDot = false;
        this.endDot = true;
      } else if (this.currentPage > this.lastPage - this.range) {
        this.frontDot = true;
        this.endDot = false;
      } else {
        this.frontDot = true;
        this.endDot = true;
      }
    },
    changePage (page) {
      if (page > 0 && page <= this.lastPage) {
        this.currentPage = page;
      }
    },
  },
});