import { API_BASE_URL } from '@/env';

export default async function fetchBanner(): Promise<Object> {
  return fetch(`${API_BASE_URL}banner`, {
    headers: {
      'accept': 'application/json'
    }
  })
    .then(response => {
      if (!response.ok) throw new Error('Failed to fetch data')

      return response.json();
    });
}
