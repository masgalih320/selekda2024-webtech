import { API_BASE_URL } from '@/env';

export default async function fetchLogin({ username, password }: { username: string, password: string }): Promise<Object> {
  return fetch(`${API_BASE_URL}login`, {
    method: 'POST',
    headers: {
      'accept': 'application/json',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      username: username,
      password: password
    })
  })
    .then(response => {
      return response.json();
    });
}