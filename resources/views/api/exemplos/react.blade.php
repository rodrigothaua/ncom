@extends('layouts.app')

@section('title', 'Exemplos React - API SIGECOM')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-dark">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Como usar a API com React.js</h5>
                </div>
                <div class="card-body">
                    <!-- Configuração Inicial -->
                    <h6 class="fw-bold">1. Configuração Inicial</h6>
                    <p>Primeiro, instale o Axios para fazer as requisições HTTP:</p>
                    <pre class="bg-light p-3 rounded"><code>npm install axios</code></pre>

                    <!-- Configuração do Axios -->
                    <h6 class="fw-bold mt-4">2. Configuração do Axios</h6>
                    <p>Crie um arquivo <code>src/services/api.js</code>:</p>
                    <pre class="bg-light p-3 rounded"><code>import axios from 'axios';

const api = axios.create({
    baseURL: 'http://seu-dominio.com/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

// Interceptor para adicionar o token em todas as requisições
api.interceptors.request.use(async config => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export default api;</code></pre>

                    <!-- Exemplo de Autenticação -->
                    <h6 class="fw-bold mt-4">3. Autenticação</h6>
                    <p>Exemplo de componente de login:</p>
                    <pre class="bg-light p-3 rounded"><code>import React, { useState } from 'react';
import api from '../services/api';

const Login = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');

    const handleLogin = async (e) => {
        e.preventDefault();
        try {
            const response = await api.post('/login', { email, password });
            const { token } = response.data;
            localStorage.setItem('token', token);
            // Redirecionar ou atualizar estado da aplicação
        } catch (error) {
            console.error('Erro no login:', error);
        }
    };

    return (
        <form onSubmit={handleLogin}>
            <input type="email" value={email} onChange={e => setEmail(e.target.value)} />
            <input type="password" value={password} onChange={e => setPassword(e.target.value)} />
            <button type="submit">Login</button>
        </form>
    );
};</code></pre>

                    <!-- Exemplos de Uso da API -->
                    <h6 class="fw-bold mt-4">4. Exemplos de Uso da API</h6>

                    <!-- Processos -->
                    <h6 class="text-primary mt-3">Processos</h6>
                    <pre class="bg-light p-3 rounded"><code>// Listar todos os processos
const getProcessos = async () => {
    try {
        const response = await api.get('/processos');
        return response.data;
    } catch (error) {
        console.error('Erro ao buscar processos:', error);
    }
};

// Buscar processo específico
const getProcesso = async (id) => {
    try {
        const response = await api.get(`/processos/${id}`);
        return response.data;
    } catch (error) {
        console.error('Erro ao buscar processo:', error);
    }
};

// Criar novo processo
const createProcesso = async (dados) => {
    try {
        const response = await api.post('/processos', dados);
        return response.data;
    } catch (error) {
        console.error('Erro ao criar processo:', error);
    }
};

// Atualizar processo
const updateProcesso = async (id, dados) => {
    try {
        const response = await api.put(`/processos/${id}`, dados);
        return response.data;
    } catch (error) {
        console.error('Erro ao atualizar processo:', error);
    }
};

// Deletar processo
const deleteProcesso = async (id) => {
    try {
        const response = await api.delete(`/processos/${id}`);
        return response.data;
    } catch (error) {
        console.error('Erro ao deletar processo:', error);
    }
};</code></pre>

                    <!-- Exemplo de Componente Completo -->
                    <h6 class="fw-bold mt-4">5. Exemplo de Componente Completo</h6>
                    <pre class="bg-light p-3 rounded"><code>import React, { useState, useEffect } from 'react';
import api from '../services/api';

const ListaProcessos = () => {
    const [processos, setProcessos] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        carregarProcessos();
    }, []);

    const carregarProcessos = async () => {
        try {
            setLoading(true);
            const response = await api.get('/processos');
            setProcessos(response.data.data);
            setError(null);
        } catch (error) {
            setError('Erro ao carregar processos');
            console.error('Erro:', error);
        } finally {
            setLoading(false);
        }
    };

    if (loading) return <div>Carregando...</div>;
    if (error) return <div>{error}</div>;

    return (
        <div>
            <h2>Lista de Processos</h2>
            <div className="table-responsive">
                <table className="table">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Descrição</th>
                            <th>Requisitante</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        {processos.map(processo => (
                            <tr key={processo.id}>
                                <td>{processo.numero_processo}</td>
                                <td>{processo.descricao}</td>
                                <td>{processo.requisitante}</td>
                                <td>
                                    <button onClick={() => handleEdit(processo.id)}>
                                        Editar
                                    </button>
                                    <button onClick={() => handleDelete(processo.id)}>
                                        Excluir
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
};</code></pre>

                    <!-- Tratamento de Erros -->
                    <h6 class="fw-bold mt-4">6. Tratamento de Erros</h6>
                    <pre class="bg-light p-3 rounded"><code>// Interceptor para tratamento global de erros
api.interceptors.response.use(
    response => response,
    error => {
        if (error.response) {
            // Erro de resposta do servidor
            switch (error.response.status) {
                case 401:
                    // Não autorizado - redirecionar para login
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                    break;
                case 422:
                    // Erro de validação
                    return Promise.reject(error.response.data.errors);
                default:
                    return Promise.reject(error.response.data);
            }
        }
        return Promise.reject(error);
    }
);</code></pre>

                    <!-- Dicas Importantes -->
                    <h6 class="fw-bold mt-4">Dicas Importantes</h6>
                    <ul>
                        <li>Sempre inclua o token de autenticação em todas as requisições</li>
                        <li>Trate os erros adequadamente, especialmente os 401 (não autorizado)</li>
                        <li>Use try/catch em todas as chamadas à API</li>
                        <li>Mantenha o estado do token em um gerenciador de estado (Redux, Context API)</li>
                        <li>Implemente um mecanismo de refresh token quando necessário</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
