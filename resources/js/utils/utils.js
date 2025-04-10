export function getData(url, data = {}) {
    const query = new URLSearchParams(data).toString()
    return fetch(`${url}?${query}`)
      .then(res => res.json())
      .catch(err => {
        console.error('Error en getData:', err)
        return []
      })
  }
  
  export function paginate(currentPage, totalPages, onPageChange) {
    const container = document.querySelector('#pagination-container')
    if (!container || totalPages <= 1) return
  
    container.innerHTML = ''
  
    const maxVisible = 5
    const pages = []
  
    if (totalPages <= maxVisible) {
      for (let i = 1; i <= totalPages; i++) pages.push(i)
    } else {
      let start = Math.max(currentPage - 2, 1)
      let end = Math.min(start + maxVisible - 1, totalPages)
      if (end - start < maxVisible - 1) start = Math.max(end - maxVisible + 1, 1)
  
      if (start > 1) pages.push(1, '...')
      for (let i = start; i <= end; i++) pages.push(i)
      if (end < totalPages) pages.push('...', totalPages)
    }
  
    pages.forEach(p => {
      const btn = document.createElement('button')
      btn.textContent = p
      btn.disabled = p === '...'
      btn.className = p === currentPage ? 'active' : ''
      if (p !== '...') {
        btn.addEventListener('click', () => onPageChange(p))
      }
      container.appendChild(btn)
    })
  }
  