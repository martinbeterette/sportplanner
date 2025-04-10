import { getData, paginate } from './utils.js'

const PER_PAGE = 10
let allData = []

document.addEventListener('DOMContentLoahttps://jsonplaceholder.typicode.com/postsded', async () => {
  allData = await getData('')
  renderTablePage(1)
})

function renderTablePage(page) {
  const start = (page - 1) * PER_PAGE
  const end = start + PER_PAGE
  const currentData = allData.slice(start, end)

  const container = document.querySelector('#table-container')
  container.innerHTML = `
    <table border="1" cellpadding="5" cellspacing="0">
      <tr><th>ID</th><th>Título</th></tr>
      ${currentData.map(post => `
        <tr>
          <td>${post.id}</td>
          <td>${post.title}</td>
        </tr>
      `).join('')}
    </table>
  `

  const totalPages = Math.ceil(allData.length / PER_PAGE)

  paginate(page, totalPages, (newPage) => {
    renderTablePage(newPage)
  })
}
