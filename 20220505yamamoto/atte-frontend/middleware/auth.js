export default function auth({store, redirect}) {
  if (!store.state.auth.authUser) {
    return redirect('/login');
  }
};
