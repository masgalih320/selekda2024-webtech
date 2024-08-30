import { API_BASE_URL } from '@/env';
import currentUser from './currentUser';

export async function fetchBanner(): Promise<Object> {
  return fetch(`${API_BASE_URL}banner`, {
    headers: {
      'accept': 'application/json'
    }
  })
    .then(response => {
      return response.json();
    });
}

export async function getBanner(id: number): Promise<Object> {
  const userdata = await currentUser();

  return fetch(`${API_BASE_URL}banner/show/${id}`, {
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

export async function updateBanner({ id, data }: { id: number, data: any }): Promise<Object> {
  const userdata = await currentUser();

  const formData = new FormData();
  formData.append('title', data.title);
  formData.append('description', data.description);
  formData.append('status', data.status);

  if (data.image) {
    formData.append('image', data.image);
  }

  return fetch(`${API_BASE_URL}banner/update/${id}`, {
    method: 'POST',
    headers: {
      'accept': 'application/json',
      'Authorization': `Bearer ${userdata.token}`,
    },
    body: formData,
  })
    .then(response => response.json());
}

export async function storeBanner({ id, data }: { id: number, data: any }): Promise<Object> {
  const userdata = await currentUser();

  const formData = new FormData();
  formData.append('title', data.title);
  formData.append('description', data.description);
  formData.append('status', data.status);

  if (data.image) {
    formData.append('image', data.image);
  }

  return fetch(`${API_BASE_URL}banner/store`, {
    method: 'POST',
    headers: {
      'accept': 'application/json',
      'Authorization': `Bearer ${userdata.token}`,
    },
    body: formData,
  })
    .then(response => response.json());
}

export async function deleteBanner(id: number): Promise<Object> {
  const userdata = await currentUser();

  return fetch(`${API_BASE_URL}banner/destroy/${id}`, {
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