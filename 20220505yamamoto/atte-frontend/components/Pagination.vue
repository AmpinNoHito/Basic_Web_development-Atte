<template>
  <ul class="pagination">
    <li 
      v-show="currentPage > 1"
      class="pagination__num pagination__num--pre"
      @click="clicked(currentPage - 1)">
      &lt;
    </li>
    <li
      v-for="page in frontPageRange" 
      :key="page"
      @click="clicked(page)"
      class="pagination__num"
      :class="{'pagination__num--active': isCurrent(page)}">
      {{page}}
    </li>
    <li v-show="frontDot" class="pagination__num">...</li>
    <li
      v-for="page in middlePageRange" 
      :key="page"
      @click="clicked(page)"
      class="pagination__num"
      :class="{'pagination__num--active': isCurrent(page)}">
      {{page}}
    </li>
    <li v-show="endDot" class="pagination__num">...</li>
    <li
      v-for="page in endPageRange" 
      :key="page"
      @click="clicked(page)"
      class="pagination__num"
      :class="{'pagination__num--active': isCurrent(page)}">
      {{page}}
    </li>
    <li
      v-show="currentPage < lastPage"
      class="pagination__num pagination__num--next"
      @click="clicked(currentPage + 1)">
      >
    </li>
  </ul>
</template>

<script>
export default {
  props: [
    'range',
    'currentPage', 
    'lastPage', 
    'frontDot',
    'endDot',
    'sizeCheck',
  ],
  computed: {
    frontPageRange () {
      if (!this.sizeCheck) {
        return this.calRange(1, this.lastPage);
      }
      return this.calRange(1, 2);
    },
    middlePageRange () {
      if (!this.sizeCheck) return [];
      let start = '';
      let end = '';
      if (this.currentPage <= this.range) {
        start = 3;
        end = this.range + 2;
      } else if (this.currentPage > this.lastPage - this.range) {
        start = this.lastPage - this.range - 1;
        end = this.lastPage - 2;
      } else {
        start = this.currentPage - Math.floor(this.range / 2);
        end = this.currentPage + Math.floor(this.range / 2);
      }
      return this.calRange(start, end);
    },
    endPageRange() {
      if (!this.sizeCheck) return [];
      return this.calRange(this.lastPage - 1, this.lastPage);
    }
  },
  methods: {
    clicked(e) {
      this.$emit('clicked', e);
    },
    calRange (start, end) {
      const range = [];
      for (let i = start; i <= end; i++) {
        range.push(i);
      }
      return range;
    },
    isCurrent(page) {
      return page === this.currentPage;
    },
  },
};
</script>

<style lang="scss">
.pagination {
  width: fit-content;
  display: flex;
  justify-content: center;
  margin: 25px auto;
  position: relative;
  &__num {
    cursor: pointer;
    display: inline-block;
    text-align: center;
    padding: 5px 8px;
    font-size: 0.8em;
    background-color: #fff;
    color: steelblue;

    &:not(:last-of-type) {
      margin-right: 5px;
    }
  }
  
  &__num--active {
    background: steelblue;
    color: #fff;
  }
  &__num--pre,
  &__num--next {
    padding: 5px 8px;
    position: absolute;
  }
  &__num--pre {
    left: -30px;
  }

  &__num--next {
    right: -30px;
  }  
}
</style>