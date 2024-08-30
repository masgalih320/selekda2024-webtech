import { API_BASE_URL } from '@/env';
import currentUser from './currentUser';

export async function fetchPortfolio(): Promise<Object> {
  return fetch(`${API_BASE_URL}portfolio`, {
    headers: {
      'accept': 'application/json'
    }
  })
    .then(response => {
      return response.json();
    });
}

export async function getPortfolio(id: number): Promise<Object> {
  const userdata = await currentUser();

  return fetch(`${API_BASE_URL}portfolio/show/${id}`, {
    method: 'GET',
    headers: {
      'accept': 'application/json',
      'Authorization': `Bearer ${userdata.token}`
    }
  })
    .then(response => {
      return response.json();
    });
}

export async function updatePortfolio({ id, data }: { id: number, data: any }): Promise<Object> {
  const userdata = await currentUser();

  const formData = new FormData();
  formData.append('title', data.title);
  formData.append('description', data.description);

  if (data.image) {
    formData.append('image', data.image);
  }

  return fetch(`${API_BASE_URL}portfolio/update/${id}`, {
    method: 'POST',
    headers: {
      'accept': 'application/json',
      'Authorization': `Bearer ${userdata.token}`,
    },
    body: formData,
  })
    .then(response => response.json());
}

export async function storePortfolio({ data }: { data: any }): Promise<Object> {
  const userdata = await currentUser();

  const formData = new FormData();
  formData.append('title', data.title);
  formData.append('description', data.description);

  if (data.image) {
    formData.append('image', data.image);
  }

  return fetch(`${API_BASE_URL}portfolio/store`, {
    method: 'POST',
    headers: {
      'accept': 'application/json',
      'Authorization': `Bearer ${userdata.token}`,
    },
    body: formData,
  })
    .then(response => response.json());
}

export async function deletePortfolio(id: number): Promise<Object> {
  const userdata = await currentUser();

  return fetch(`${API_BASE_URL}portfolio/destroy/${id}`, {
    method: 'DELETE',
    headers: {
      'accept': 'application/json',
      'Authorization': `Bearer ${userdata.token}`
    }
  })
    .then(response => {
      return response.json();
    });
}