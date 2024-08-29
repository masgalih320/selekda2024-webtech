import { API_BASE_URL } from '@/env';

export default async function fetchPortfolio(): Promise<Object> {
  return fetch(`${API_BASE_URL}portfolio`, {
    headers: {
      'accept': 'application/json'
    }
  })
    .then(response => {
      if (!response.ok) throw new Error('Failed to fetch data')

      return response.json();
    });
}
