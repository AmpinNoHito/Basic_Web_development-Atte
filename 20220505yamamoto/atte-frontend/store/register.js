export const state = () => ({
  canStartWork: true,
  canEndWork: false,
  canStartRest: true,
  canEndRest: false,
});

export const mutations = {
  toggleStartWork(state) {
    state.canStartWork = !state.canStartWork;
  },
  toggleEndWork(state) {
    state.canEndWork = !state.canEndWork;
  },
  toggleStartRest(state) {
    state.canStartRest = !state.canStartRest;
  },
  toggleEndRest(state) {
    state.canEndRest = !state.canEndRest;
  },
  resetState(state) {
    state.canStartWork = true;
    state.canEndWork = false;
    state.canStartRest = true;
    state.canEndRest = false;
  },
  setByUserInfo(state, userInfo) {
    const work = userInfo.works[userInfo.works.length - 1];
    const rest = work?.rests[work.rests.length - 1];
    const year = new Date().getFullYear();
    const month = new Date().getMonth() + 1;
    const date = new Date().getDate();
    const today = `${year}-${(month < 10) ? `0${month}` : month}-${(date < 10) ? `0${date}` : date}`;
    
    if (work?.date !== today) {
      state.canStartWork = true;
      state.canEndWork = false;
      state.canStartRest = true;
      state.canEndRest = false;
    } else if (work?.start === '00:00:00.00' && work?.end === '23:59:59.99'
              || work?.end === '23:59:59.99' && rest?.end === '23:59:59.99') {
      state.canStartWork = false;
      state.canEndWork = true;
      state.canStartRest = false;
      state.canEndRest = true;
    } else if (work?.end === '23:59:59.99') {
      state.canStartWork = false;
      state.canEndWork = true;
    } else if (work?.start !== '00:00:00.00' && work?.end !== '23:59:59.99') {
      state.canStartWork = false;
      state.canEndWork = false;
      state.canStartRest = false;
      state.canEndRest = false;
    } else {
      state.canStartWork = true;
      state.canEndWork = false;
      state.canStartRest = true;
      state.canEndRest = false;
    }
  },
}
