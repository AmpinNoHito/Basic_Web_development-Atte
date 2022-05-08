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
  }
}
