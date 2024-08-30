export default async function currentUser() {
  const session = localStorage.getItem('selekda_session');

  if (session) {
    try {
      return JSON.parse(session);
    } catch (error) {
      return null;
    }
  } else {
    return null;
  }
}
